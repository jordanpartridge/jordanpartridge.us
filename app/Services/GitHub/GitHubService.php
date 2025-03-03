<?php

namespace App\Services\GitHub;

use App\Http\Integrations\GitHubApi\GitHubApi;
use App\Http\Integrations\GitHubApi\Requests\GetRepositories;
use App\Http\Integrations\GitHubApi\Requests\GetRepository;
use App\Models\GithubRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    private GitHubApi $client;

    public function __construct()
    {
        $this->client = new GitHubApi(config('services.github.token'));
    }

    /**
     * Fetch repositories for a specific user
     */
    public function getRepositories(string $username, int $perPage = 100): Collection
    {
        try {
            $request = new GetRepositories($username, $perPage);
            $response = $this->client->send($request);

            if ($response->failed()) {
                Log::error('Failed to fetch GitHub repositories', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return collect();
            }

            return collect($response->json());
        } catch (\Exception $e) {
            Log::error('Exception when fetching GitHub repositories', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return collect();
        }
    }

    /**
     * Fetch a single repository
     */
    public function getRepository(string $username, string $repository): ?array
    {
        try {
            $request = new GetRepository($username, $repository);
            $response = $this->client->send($request);

            if ($response->failed()) {
                Log::error('Failed to fetch GitHub repository', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception when fetching GitHub repository', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Sync a repository in the database
     */
    public function syncRepository(string $username, string $repository): ?GithubRepository
    {
        $repoData = $this->getRepository($username, $repository);

        if (!$repoData) {
            return null;
        }

        // Detect technologies from topics and language
        $technologies = $repoData['topics'] ?? [];
        if (isset($repoData['language']) && !in_array($repoData['language'], $technologies)) {
            $technologies[] = $repoData['language'];
        }

        // Find or create the repository record
        $githubRepo = GithubRepository::updateOrCreate(
            [
                'repository' => $repository,
            ],
            [
                'name'            => $repoData['name'],
                'description'     => $repoData['description'] ?? null,
                'url'             => $repoData['html_url'],
                'technologies'    => $technologies,
                'stars_count'     => $repoData['stargazers_count'] ?? 0,
                'forks_count'     => $repoData['forks_count'] ?? 0,
                'last_fetched_at' => now(),
            ]
        );

        return $githubRepo;
    }

    /**
     * Sync all active repositories in the database
     */
    public function syncAllRepositories(): Collection
    {
        $repos = GithubRepository::active()->get();
        $updated = collect();

        foreach ($repos as $repo) {
            $parts = explode('/', parse_url($repo->url, PHP_URL_PATH));
            $username = $parts[1] ?? 'jordanpartridge';
            $repository = $parts[2] ?? $repo->repository;

            $updatedRepo = $this->syncRepository($username, $repository);

            if ($updatedRepo) {
                $updated->push($updatedRepo);
            }
        }

        return $updated;
    }
}
