<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class AnalyzeErrorWithMe extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'logs:analyze-error
                            {--recent : Analyze the most recent error}
                            {--hours=24 : Hours to look back}
                            {--create-issue : Create GitHub issue}
                            {--interactive : Interactive mode for detailed analysis}';

    /**
     * The console command description.
     */
    protected $description = 'Analyze application errors and provide intelligent insights';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Starting intelligent error analysis...');

        try {
            $errors = $this->option('recent') 
                ? $this->getRecentErrors() 
                : $this->getErrorsInTimeframe((int) $this->option('hours'));

            if (empty($errors)) {
                $this->info('✅ No errors found in the specified timeframe!');
                return Command::SUCCESS;
            }

            $this->info('Found ' . count($errors) . ' error(s) to analyze.');

            foreach ($errors as $index => $error) {
                $this->analyzeError($error, $index + 1);
                
                if ($this->option('interactive') && $index < count($errors) - 1) {
                    if (!$this->confirm('Continue to next error?')) {
                        break;
                    }
                }
            }

            if ($this->option('create-issue')) {
                $this->createGitHubIssues($errors);
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Analysis failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Get recent errors from log files.
     * 
     * @return array<int, array<string, mixed>>
     */
    private function getRecentErrors(): array
    {
        return $this->getErrorsInTimeframe(24);
    }

    /**
     * Get errors within a specific timeframe.
     * 
     * @return array<int, array<string, mixed>>
     */
    private function getErrorsInTimeframe(int $hours): array
    {
        $errors = [];
        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            return $errors;
        }

        $files = File::files($logPath);
        $cutoffTime = Carbon::now()->subHours($hours);

        foreach ($files as $file) {
            if (!str_ends_with($file->getFilename(), '.log')) {
                continue;
            }

            $content = File::get($file->getPathname());
            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                if (empty($line) || !str_contains($line, 'ERROR')) {
                    continue;
                }

                $parsedError = $this->parseLogLine($line);
                if ($parsedError && Carbon::parse($parsedError['timestamp'])->gte($cutoffTime)) {
                    $errors[] = $parsedError;
                }
            }
        }

        return array_slice($errors, -10); // Limit to last 10 errors
    }

    /**
     * Parse a log line into structured data.
     * 
     * @return array<string, mixed>|null
     */
    private function parseLogLine(string $line): ?array
    {
        // Match Laravel log format: [YYYY-MM-DD HH:MM:SS] environment.LEVEL: message
        if (!preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+)/', $line, $matches)) {
            return null;
        }

        return [
            'timestamp' => $matches[1],
            'level' => $matches[2],
            'message' => $matches[3],
            'context' => $this->extractContext($matches[3]),
            'stack_trace' => $this->extractStackTrace($matches[3])
        ];
    }

    /**
     * Analyze a single error.
     * 
     * @param array<string, mixed> $error
     */
    private function analyzeError(array $error, int $index): void
    {
        $this->newLine();
        $this->info("📊 Error Analysis #{$index}");
        $this->line("Time: {$error['timestamp']}");
        $this->line("Level: {$error['level']}");
        $this->line("Message: " . substr($error['message'], 0, 100) . '...');

        // Categorize error
        $category = $this->categorizeError($error);
        $this->line("Category: {$category}");

        // Extract application files
        $appFiles = $this->extractApplicationFiles($error);
        if (!empty($appFiles)) {
            $this->line("Application Files:");
            foreach ($appFiles as $file) {
                $this->line("  - {$file}");
            }
        }

        // Provide recommendations
        $recommendations = $this->getRecommendations($error, $category);
        if (!empty($recommendations)) {
            $this->line("Recommendations:");
            foreach ($recommendations as $recommendation) {
                $this->line("  • {$recommendation}");
            }
        }
    }

    /**
     * Categorize error based on message content.
     */
    private function categorizeError(array $error): string
    {
        $message = strtolower($error['message']);

        if (str_contains($message, 'database') || str_contains($message, 'sql')) {
            return 'Database';
        }
        if (str_contains($message, 'gmail') || str_contains($message, 'email')) {
            return 'Gmail Integration';
        }
        if (str_contains($message, 'class not found') || str_contains($message, 'undefined method')) {
            return 'Code Structure';
        }
        if (str_contains($message, 'permission') || str_contains($message, 'access')) {
            return 'Permissions';
        }

        return 'General';
    }

    /**
     * Extract context from error message.
     * 
     * @return array<string, mixed>
     */
    private function extractContext(string $message): array
    {
        $context = [];

        // Extract file paths
        if (preg_match_all('/\/[a-zA-Z0-9\/_\-\.]+\.php/', $message, $matches)) {
            $context['files'] = $matches[0];
        }

        // Extract line numbers
        if (preg_match_all('/:(\d+)/', $message, $matches)) {
            $context['lines'] = $matches[1];
        }

        return $context;
    }

    /**
     * Extract stack trace information.
     * 
     * @return array<string, string>
     */
    private function extractStackTrace(string $message): array
    {
        $traces = [];
        
        if (str_contains($message, 'Stack trace:')) {
            $parts = explode('Stack trace:', $message);
            if (count($parts) > 1) {
                $stackPart = $parts[1];
                $lines = explode("\n", $stackPart);
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line) && str_starts_with($line, '#')) {
                        $traces[] = $line;
                    }
                }
            }
        }

        return $traces;
    }

    /**
     * Extract application-specific files from error.
     * 
     * @param array<string, mixed> $error
     * @return array<string>
     */
    private function extractApplicationFiles(array $error): array
    {
        $files = [];
        $message = $error['message'];

        if (preg_match_all('/\/app\/[a-zA-Z0-9\/_\-\.]+\.php/', $message, $matches)) {
            $files = array_merge($files, $matches[0]);
        }

        return array_unique($files);
    }

    /**
     * Get recommendations based on error analysis.
     * 
     * @param array<string, mixed> $error
     * @return array<string>
     */
    private function getRecommendations(array $error, string $category): array
    {
        $recommendations = [];

        switch ($category) {
            case 'Database':
                $recommendations[] = 'Check database connection and credentials';
                $recommendations[] = 'Verify migration status: php artisan migrate:status';
                $recommendations[] = 'Check for long-running queries or locks';
                break;

            case 'Gmail Integration':
                $recommendations[] = 'Verify Gmail API credentials and token validity';
                $recommendations[] = 'Check API rate limits and quotas';
                $recommendations[] = 'Review OAuth permissions and scopes';
                break;

            case 'Code Structure':
                $recommendations[] = 'Run composer dump-autoload to refresh class maps';
                $recommendations[] = 'Check for missing imports or use statements';
                $recommendations[] = 'Verify namespace declarations';
                break;

            case 'Permissions':
                $recommendations[] = 'Check file and directory permissions';
                $recommendations[] = 'Verify storage directory is writable';
                $recommendations[] = 'Review user role and permission assignments';
                break;

            default:
                $recommendations[] = 'Review the full error message and stack trace';
                $recommendations[] = 'Check application logs for related errors';
                break;
        }

        return $recommendations;
    }

    /**
     * Create GitHub issues for errors if requested.
     * 
     * @param array<int, array<string, mixed>> $errors
     */
    private function createGitHubIssues(array $errors): void
    {
        $this->info('Creating GitHub issues for errors...');

        foreach ($errors as $index => $error) {
            $title = "🐛 Error: " . substr($error['message'], 0, 50) . '...';
            $body = $this->formatErrorForGitHub($error);

            $this->call('issues:create-as-bot', [
                'title' => $title,
                'body' => $body,
                '--labels' => 'bug,needs-investigation'
            ]);
        }
    }

    /**
     * Format error for GitHub issue creation.
     * 
     * @param array<string, mixed> $error
     */
    private function formatErrorForGitHub(array $error): string
    {
        $body = "## Error Details\n\n";
        $body .= "**Timestamp:** {$error['timestamp']}\n";
        $body .= "**Level:** {$error['level']}\n";
        $body .= "**Message:**\n```\n{$error['message']}\n```\n\n";

        if (!empty($error['context']['files'])) {
            $body .= "**Files Involved:**\n";
            foreach ($error['context']['files'] as $file) {
                $body .= "- {$file}\n";
            }
            $body .= "\n";
        }

        $body .= "**Recent Commits:**\n";
        $commits = $this->getRecentCommits();
        foreach (array_slice($commits, 0, 3) as $commit) {
            $body .= "- {$commit}\n";
        }

        return $body;
    }

    /**
     * Get recent Git commits for context.
     * 
     * @return array<string>
     */
    private function getRecentCommits(): array
    {
        $result = Process::run('git log --oneline -5 2>/dev/null');

        if ($result->successful() && !empty(trim($result->output()))) {
            return explode("\n", trim($result->output()));
        }

        return [];
    }
}
