<?php

namespace App\Services\GitHub;

use App\Models\GithubRepository;
use App\Settings\GitHubSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use JordanPartridge\GithubClient\Facades\Github;
use JordanPartridge\GithubClient\ValueObjects\Repo;

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
        if (!$this->settings->getToken()) {
            Log::error('GitHub API token not set. Please configure it in GitHub Settings');
            throw new \Exception('GitHub API token not set. Please configure it in GitHub Settings');
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
            $repoObject = new Repo("{$parts['owner']}/{$parts['repo']}");
            
            // Get repository data
            $repoData = Github::repos()->get($repoObject);
            
            if (!$repoData) {
                Log::error('Failed to fetch repository data', [
                    'owner' => $parts['owner'],
                    'repo' => $parts['repo'],
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
                'name' => $repoData->name,
                'description' => $repoData->description ?? $repository->description,
                'technologies' => $technologies,
                'stars_count' => $repoData->stargazers_count ?? 0,
                'forks_count' => $repoData->forks_count ?? 0,
                'last_fetched_at' => now(),
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error syncing repository', [
                'repository' => $repository->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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
        if (!$this->settings->getToken()) {
            Log::error('GitHub API token not set. Please configure it in GitHub Settings');
            throw new \Exception('GitHub API token not set. Please configure it in GitHub Settings');
        }
        
        $repositories = GithubRepository::where('is_active', true)->get();
        $success = collect();
        
        if ($repositories->isEmpty()) {
            throw new \Exception('No active repositories found. Please add repositories through the admin panel first.');
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
            throw new \Exception('GitHub API token not set. Please configure it in GitHub Settings');
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
                'name' => $repoData->name,
                'repository' => $repository,
                'description' => $repoData->description ?? null,
                'url' => $repoData->html_url,
                'technologies' => $technologies,
                'stars_count' => $repoData->stargazers_count ?? 0,
                'forks_count' => $repoData->forks_count ?? 0,
                'is_active' => true,
                'featured' => false,
                'display_order' => 0,
                'last_fetched_at' => now(),
            ]);
            
            return $repo;
        } catch (\Exception $e) {
            Log::error('Error fetching and creating repository', [
                'username' => $username,
                'repository' => $repository,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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
            'repo' => $parts[1] ?? null,
        ];
    }
}