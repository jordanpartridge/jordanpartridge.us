<?php

namespace App\Services\Gmail;

class EmailClassificationService
{
    private array $config;

    public function __construct()
    {
        $this->config = config('gmail-integration.classification', []);
    }

    /**
     * Classify an email message and return classification data
     */
    public function classify(array $message): array
    {
        $from = $message['from'] ?? '';
        $subject = strtolower($message['subject'] ?? '');
        $snippet = $message['snippet'] ?? '';

        return [
            'is_github_notification'  => $this->isGitHubNotification($from),
            'is_github_pr'            => $this->isGitHubPR($from, $subject),
            'is_github_issue'         => $this->isGitHubIssue($from, $subject),
            'is_github_action'        => $this->isGitHubAction($from, $subject),
            'is_laravel'              => $this->isLaravel($from, $subject),
            'is_service_notification' => $this->isServiceNotification($from),
            'github_urls'             => $this->extractGitHubUrls($snippet),
            'category'                => $this->determineCategory($message),
        ];
    }

    /**
     * Check if email is from GitHub
     */
    public function isGitHubNotification(string $from): bool
    {
        $githubDomains = $this->config['github_domains'] ?? ['github.com', 'notifications@github.com'];

        foreach ($githubDomains as $domain) {
            if (str_contains($from, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if GitHub email is about a Pull Request
     */
    public function isGitHubPR(string $from, string $subject): bool
    {
        if (!$this->isGitHubNotification($from)) {
            return false;
        }

        $prKeywords = $this->config['github_pr_keywords'] ?? ['pull request', 'pr ', 'merged'];

        foreach ($prKeywords as $keyword) {
            if (str_contains($subject, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if GitHub email is about an Issue
     */
    public function isGitHubIssue(string $from, string $subject): bool
    {
        if (!$this->isGitHubNotification($from)) {
            return false;
        }

        $issueKeywords = $this->config['github_issue_keywords'] ?? ['issue', 'bug'];

        foreach ($issueKeywords as $keyword) {
            if (str_contains($subject, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if GitHub email is about Actions/CI
     */
    public function isGitHubAction(string $from, string $subject): bool
    {
        if (!$this->isGitHubNotification($from)) {
            return false;
        }

        $actionKeywords = $this->config['github_action_keywords'] ?? ['action', 'workflow', 'build', 'deploy'];

        foreach ($actionKeywords as $keyword) {
            if (str_contains($subject, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if email is Laravel-related
     */
    public function isLaravel(string $from, string $subject): bool
    {
        $laravelKeywords = $this->config['laravel_keywords'] ?? ['laravel'];

        foreach ($laravelKeywords as $keyword) {
            if (str_contains(strtolower($from), $keyword) || str_contains($subject, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if email is from a service/automated account
     */
    public function isServiceNotification(string $from): bool
    {
        $serviceDomains = $this->config['service_domains'] ?? ['noreply', 'no-reply', 'donotreply'];

        foreach ($serviceDomains as $domain) {
            if (str_contains($from, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract GitHub URLs from email content
     */
    public function extractGitHubUrls(string $content): array
    {
        if (!config('gmail-integration.github_integration.extract_urls', true)) {
            return [];
        }

        preg_match_all('/https:\/\/github\.com\/[^\s]+/', $content, $matches);
        $urls = array_unique($matches[0] ?? []);

        $maxUrls = config('gmail-integration.github_integration.max_urls_per_email', 3);
        return array_slice($urls, 0, $maxUrls);
    }

    /**
     * Determine the email category for business logic
     */
    public function determineCategory(array $message): string
    {
        // Check if it's a client email
        if ($message['isClient'] ?? false) {
            return 'client';
        }

        // Check if it's a prospect inquiry
        if (($message['category'] ?? '') === 'prospect_inquiry') {
            return 'prospect_inquiry';
        }

        // Check if it's GitHub-related
        if ($this->isGitHubNotification($message['from'] ?? '')) {
            return 'github';
        }

        // Check if it's Laravel-related
        if ($this->isLaravel($message['from'] ?? '', strtolower($message['subject'] ?? ''))) {
            return 'laravel';
        }

        // Check if it's a service notification
        if ($this->isServiceNotification($message['from'] ?? '')) {
            return 'service';
        }

        return 'general';
    }

    /**
     * Get appropriate label for GitHub link based on URL and message
     */
    public function getGitHubLinkLabel(string $url, array $message): string
    {
        $subject = strtolower($message['subject'] ?? '');

        if (str_contains($url, '/pull/')) {
            return 'View PR';
        }

        if (str_contains($url, '/issues/')) {
            return 'View Issue';
        }

        if (str_contains($url, '/actions') || str_contains($url, '/runs/')) {
            return 'View Action';
        }

        if ($this->isGitHubPR($message['from'] ?? '', $subject)) {
            return 'View PR';
        }

        if ($this->isGitHubIssue($message['from'] ?? '', $subject)) {
            return 'View Issue';
        }

        if ($this->isGitHubAction($message['from'] ?? '', $subject)) {
            return 'View Action';
        }

        return 'View on GitHub';
    }
}
