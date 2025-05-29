<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class CreateBotIssue extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'issues:create-as-bot
                            {title : Issue title}
                            {body : Issue body}
                            {--labels= : Comma-separated labels}';

    /**
     * The console command description.
     */
    protected $description = 'Create a GitHub issue with bot attribution';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $title = (string) $this->argument('title');
        $body = (string) $this->argument('body');
        $labels = $this->option('labels') ? explode(',', (string) $this->option('labels')) : [];

        // Validate inputs
        if (empty($title) || empty($body)) {
            $this->error('Title and body are required');
            return Command::FAILURE;
        }

        // Check for authentication methods
        if (
            !config('services.github.app_token')
            && !config('services.github.bot_token')
            && !Process::run(['which', 'gh'])->successful()
        ) {
            $this->error('No GitHub authentication method available. Configure tokens or install GitHub CLI.');
            return Command::FAILURE;
        }

        // Try different authentication methods
        if (config('services.github.app_token') || config('services.github.bot_token')) {
            return $this->createIssueWithToken($title, $body, $labels);
        }

        return $this->createIssueWithAttribution($title, $body, $labels);
    }

    /**
     * Create issue using API tokens
     * 
     * @param array<string> $labels
     */
    private function createIssueWithToken(string $title, string $body, array $labels): int
    {
        $this->info('Creating issue with API token...');

        $token = config('services.github.app_token') ?: config('services.github.bot_token');
        $repository = config('services.github.repository');

        if (!$repository) {
            $this->error('GitHub repository not configured');
            return Command::FAILURE;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Laravel-Bot/1.0'
            ])->post("https://api.github.com/repos/{$repository}/issues", [
                'title' => '🤖 [AUTO] ' . $title,
                'body' => $this->formatBodyWithAttribution($body),
                'labels' => array_merge($labels, ['automated', 'bot-created'])
            ]);

            if ($response->successful()) {
                $issueData = $response->json();
                $this->info("✅ Issue created: {$issueData['html_url']}");
                return Command::SUCCESS;
            }

            $this->error('API request failed: ' . $response->body());
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error('Failed to create issue: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Create issue using GitHub CLI with attribution
     * 
     * @param array<string> $labels
     */
    private function createIssueWithAttribution(string $title, string $body, array $labels): int
    {
        $this->info('Creating issue with automation attribution...');

        // Validate that gh CLI is available
        if (!Process::run(['which', 'gh'])->successful()) {
            $this->error("GitHub CLI (gh) is not installed or not in PATH");
            return Command::FAILURE;
        }

        // Add automation attribution to body
        $automatedBody = $this->formatBodyWithAttribution($body);

        $command = ['gh', 'issue', 'create'];

        // Add repository context for safety
        if (config('services.github.repository')) {
            $command[] = '--repo';
            $command[] = config('services.github.repository');
        }

        $command = array_merge($command, [
            '--title', '🤖 [AUTO] ' . $title,
            '--body', $automatedBody
        ]);

        // Add labels if provided
        if (!empty($labels)) {
            $command[] = '--label';
            $command[] = implode(',', array_merge($labels, ['automated', 'bot-created']));
        }

        try {
            $result = Process::run($command);

            if ($result->successful()) {
                $this->info("✅ Issue created: " . trim($result->output()));
                return Command::SUCCESS;
            }

            $this->error("Failed to create issue: " . $result->errorOutput());
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error('Command execution failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Format issue body with automation attribution
     */
    private function formatBodyWithAttribution(string $body): string
    {
        $automatedBody = "## 🤖 Automated Issue\n\n";
        $automatedBody .= "> This issue was automatically created by the application's monitoring system.\n\n";
        $automatedBody .= "### Issue Details\n\n";
        $automatedBody .= $body;
        $automatedBody .= "\n\n---\n\n";
        $automatedBody .= "*Generated at: " . now()->toDateTimeString() . "*\n";
        $automatedBody .= "*Generated by: Laravel Log Analyzer*";

        return $automatedBody;
    }

    /**
     * Validate GitHub CLI installation and authentication
     */
    private function validateGitHubCli(): bool
    {
        // Check if gh CLI is installed
        if (!Process::run(['which', 'gh'])->successful()) {
            $this->error('GitHub CLI (gh) is not installed');
            $this->line('Install it from: https://cli.github.com/');
            return false;
        }

        // Check if authenticated
        $authCheck = Process::run(['gh', 'auth', 'status']);
        if (!$authCheck->successful()) {
            $this->error('GitHub CLI is not authenticated');
            $this->line('Run: gh auth login');
            return false;
        }

        return true;
    }

    /**
     * Get current repository name from git config
     */
    private function getCurrentRepository(): ?string
    {
        $result = Process::run(['git', 'config', '--get', 'remote.origin.url']);
        
        if (!$result->successful()) {
            return null;
        }

        $url = trim($result->output());
        
        // Parse GitHub URL to get owner/repo
        if (preg_match('/github\.com[\/:]([^\/]+)\/([^\/\.]+)/', $url, $matches)) {
            return $matches[1] . '/' . $matches[2];
        }

        return null;
    }

    /**
     * Display help information for the command
     */
    private function displayHelp(): void
    {
        $this->newLine();
        $this->line('<info>GitHub Issue Creation Help</info>');
        $this->line('');
        $this->line('<comment>Authentication Methods:</comment>');
        $this->line('1. GitHub App Token: Set GITHUB_APP_TOKEN in .env');
        $this->line('2. GitHub Bot Token: Set GITHUB_BOT_TOKEN in .env');
        $this->line('3. GitHub CLI: Install and authenticate with `gh auth login`');
        $this->line('');
        $this->line('<comment>Configuration:</comment>');
        $this->line('Set GITHUB_REPOSITORY in .env (format: owner/repo)');
        $this->line('');
        $this->line('<comment>Example Usage:</comment>');
        $this->line('php artisan issues:create-as-bot "Bug Report" "Description of the bug" --labels=bug,high-priority');
    }
}
