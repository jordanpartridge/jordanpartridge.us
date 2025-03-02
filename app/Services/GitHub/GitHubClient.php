<?php

namespace App\Services\GitHub;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class GitHubClient
{
    protected string $baseUrl = 'https://api.github.com';
    protected string $token;
    protected int $cacheTime = 3600; // 1 hour
    
    public function __construct(?string $token = null)
    {
        $this->token = $token ?? config('services.github.token');
    }
    
    /**
     * Get a user's repositories
     */
    public function getUserRepositories(string $username, array $options = []): Collection
    {
        $cacheKey = "github_user_repos_{$username}_" . md5(json_encode($options));
        
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($username, $options) {
            $response = $this->makeRequest('GET', "/users/{$username}/repos", $options);
            
            if (!$response->successful()) {
                return collect();
            }
            
            return collect($response->json());
        });
    }
    
    /**
     * Get a single repository by owner and repo name
     */
    public function getRepository(string $owner, string $repo): ?array
    {
        $cacheKey = "github_repo_{$owner}_{$repo}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($owner, $repo) {
            $response = $this->makeRequest('GET', "/repos/{$owner}/{$repo}");
            
            if (!$response->successful()) {
                return null;
            }
            
            return $response->json();
        });
    }
    
    /**
     * Get repository topics
     */
    public function getRepositoryTopics(string $owner, string $repo): array
    {
        $cacheKey = "github_repo_topics_{$owner}_{$repo}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($owner, $repo) {
            $response = $this->makeRequest('GET', "/repos/{$owner}/{$repo}/topics");
            
            if (!$response->successful()) {
                return [];
            }
            
            return $response->json()['names'] ?? [];
        });
    }
    
    /**
     * Make an HTTP request to the GitHub API
     */
    protected function makeRequest(string $method, string $endpoint, array $options = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'jordanpartridge.us',
        ];
        
        if ($this->token) {
            $headers['Authorization'] = "token {$this->token}";
        }
        
        return Http::withHeaders($headers)->$method($url, $options);
    }
    
    /**
     * Parse repository URL to get owner and repo name
     */
    public function parseRepositoryUrl(string $url): array
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