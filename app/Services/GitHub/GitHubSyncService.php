<?php

namespace App\Services\GitHub;

use Exception;
use App\Models\GithubRepository;
use App\Settings\GitHubSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use JordanPartridge\GithubClient\Facades\Github;
use JordanPartridge\GithubClient\ValueObjects\Repo;

// Added missing import

class GitHubSyncService
{
    /**
     * Sync a single repository
     */
    protected GitHubSettings $settings;

    public function __construct(GitHubSettings $settings)
    {
        $this->settings = $settings;
    }

    public function syncRepository(GithubRepository $repository): bool
    {
        // First check if GitHub token is set
        if (!$this->settings->getToken() ?? config('services.github.token')) {
            Log::error('GitHub API token not set. Please configure it in GitHub Settings');
            throw new Exception('GitHub API token not set. Please configure it in GitHub Settings'); // Fixed \Exception
        }

        try {
            // Parse repository name from URL
            $parts = $this->parseRepositoryUrl($repository->url);

            if (!$parts['owner'] || !$parts['repo']) {
                Log::error('Failed to parse repository URL', [
                    'url' => $repository->url,
                ]);
                return false;
            }

            // Create a repo value object
            $repoObject = Repo::fromFullName("{$parts['owner']}/{$parts['repo']}");

            // Get repository data
            $repoData = Github::repos()->get($repoObject);

            if (!$repoData) {
                Log::error('Failed to fetch repository data', [
                    'owner' => $parts['owner'],
                    'repo'  => $parts['repo'],
                ]);
                return false;
            }

            // Prepare technologies array from topics
            $technologies = $repoData->topics ?? [];
            if ($repoData->language && !in_array($repoData->language, $technologies)) {
                $technologies[] = $repoData->language;
            }

            // Update repository
            $repository->update([
                'name'            => $repoData->name,
                'description'     => $repoData->description ?? $repository->description,
                'technologies'    => $technologies,
                'stars_count'     => $repoData->stargazers_count ?? 0,
                'forks_count'     => $repoData->forks_count ?? 0,
                'last_fetched_at' => now(),
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error syncing repository', [
                'repository' => $repository->id,
                'message'    => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Sync all repositories
     */
    public function syncAllRepositories(): Collection
    {
        // First check if GitHub token is set
        if (!$this->settings->getToken() ?? config('services.github.token')) {
            Log::error('GitHub API token not set. Please configure it in GitHub Settings');
            throw new Exception('GitHub API token not set. Please configure it in GitHub Settings'); // Fixed \Exception
        }

        $repositories = GithubRepository::where('is_active', true)->get();
        $success = collect();

        // If no repositories in the database, fetch from GitHub and create them
        if ($repositories->isEmpty()) {
            // Get the username from settings
            $username = $this->settings->username;

            // Create a GitHub client
            $client = new GitHubClient($this->settings->getToken()); // Fixed \App\Services\GitHub\GitHubClient

            // Fetch repositories from GitHub
            $githubRepos = $client->getUserRepositories($username, ['type' => 'all']);

            // Create repositories in the database
            foreach ($githubRepos as $repo) {
                // Skip forks unless you want to include them
                if ($repo['fork'] ?? false) {
                    continue;
                }

                // Create basic repository record
                $newRepo = GithubRepository::create([
                    'name'            => $repo['name'],
                    'repository'      => $repo['name'],
                    'description'     => $repo['description'] ?? null,
                    'url'             => $repo['html_url'],
                    'technologies'    => [$repo['language']],
                    'stars_count'     => $repo['stargazers_count'] ?? 0,
                    'forks_count'     => $repo['forks_count'] ?? 0,
                    'featured'        => false,
                    'is_active'       => true,
                    'display_order'   => 0,
                    'last_fetched_at' => now(),
                ]);

                // Update repositories collection with the new ones
                $repositories->push($newRepo);
            }
        }

        foreach ($repositories as $repository) {
            if ($this->syncRepository($repository)) {
                $success->push($repository->fresh());
            }
        }

        return $success;
    }

    /**
     * Fetch and create a new repository from GitHub
     */
    public function fetchAndCreateRepository(string $username, string $repository): ?GithubRepository
    {
        // First check if GitHub token is set
        if (!$this->settings->getToken()) {
            Log::error('GitHub API token not set. Please configure it in GitHub Settings');
            throw new Exception('GitHub API token not set. Please configure it in GitHub Settings'); // Fixed \Exception
        }

        try {
            // Create a repo value object
            $repoObject = new Repo("{$username}/{$repository}");

            // Get repository data
            $repoData = Github::repos()->get($repoObject);

            if (!$repoData) {
                return null;
            }

            // Prepare technologies array from topics
            $technologies = $repoData->topics ?? [];
            if ($repoData->language && !in_array($repoData->language, $technologies)) {
                $technologies[] = $repoData->language;
            }

            // Create repository
            $repo = GithubRepository::create([
                'name'            => $repoData->name,
                'repository'      => $repository,
                'description'     => $repoData->description ?? null,
                'url'             => $repoData->html_url,
                'technologies'    => $technologies,
                'stars_count'     => $repoData->stargazers_count ?? 0,
                'forks_count'     => $repoData->forks_count ?? 0,
                'is_active'       => true,
                'featured'        => false,
                'display_order'   => 0,
                'last_fetched_at' => now(),
            ]);

            return $repo;
        } catch (Exception $e) {
            Log::error('Error fetching and creating repository', [
                'username'   => $username,
                'repository' => $repository,
                'message'    => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Parse repository URL to get owner and repo name
     */
    private function parseRepositoryUrl(string $url): array
    {
        $parts = explode('/', parse_url($url, PHP_URL_PATH));

        // Remove empty elements
        $parts = array_filter($parts);

        // Reindex array
        $parts = array_values($parts);

        return [
            'owner' => $parts[0] ?? null,
            'repo'  => $parts[1] ?? null,
        ];
    }
}
