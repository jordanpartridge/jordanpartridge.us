<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class AnalyzeErrorWithMe extends Command
{
    protected $signature = 'logs:analyze-error
                            {--recent : Analyze the most recent error}
                            {--hours=24 : Hours to look back}
                            {--create-issue : Create GitHub issue}
                            {--fix : Attempt to fix the issue}
                            {--bot : Create issue as bot account}';

    protected $description = 'Extract error and let Claude Code (me) analyze it directly';

    public function handle()
    {
        $this->info('🔍 Extracting error for analysis...');

        // Get the error
        $error = $this->getErrorToAnalyze();

        if (!$error) {
            $this->error('No errors found in the specified timeframe.');
            return Command::FAILURE;
        }

        // Display the error details
        $this->displayError($error);

        // Create context for analysis
        $context = $this->buildAnalysisContext($error);

        // Save context to a file that I can read
        $contextFile = storage_path('logs/current-error-analysis.md');
        file_put_contents($contextFile, $context);

        $this->info("\n📄 Error context saved to: {$contextFile}");
        $this->info("🤖 Claude Code can now analyze this error using the Read tool!");

        // Show what the analysis should include
        $this->showAnalysisInstructions();

        return Command::SUCCESS;
    }

    protected function getErrorToAnalyze(): ?array
    {
        $logPath = storage_path('logs/laravel.log');
        $cutoffTime = Carbon::now()->subHours($this->option('hours'));

        if (!file_exists($logPath)) {
            return null;
        }

        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines);

        $currentEntry = [];
        $inError = false;

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(ERROR|CRITICAL|ALERT|EMERGENCY):/', $line, $matches)) {
                $timestamp = Carbon::parse($matches[1]);

                if ($timestamp->gte($cutoffTime)) {
                    $currentEntry = [
                        'timestamp'  => $matches[1],
                        'level'      => $matches[2],
                        'message'    => trim(substr($line, strpos($line, ':') + 1)),
                        'stacktrace' => []
                    ];
                    $inError = true;
                    continue;
                }
            }

            if ($inError) {
                if (preg_match('/^\[\d{4}-\d{2}-\d{2}/', $line)) {
                    break;
                }

                if (str_starts_with($line, '#') || str_contains($line, ' at ')) {
                    $currentEntry['stacktrace'][] = $line;
                } else {
                    $currentEntry['message'] .= "\n" . $line;
                }
            }
        }

        return !empty($currentEntry) ? $currentEntry : null;
    }

    protected function displayError(array $error): void
    {
        $this->info("📊 Found Error:");
        $this->line("• Timestamp: {$error['timestamp']}");
        $this->line("• Level: {$error['level']}");
        $this->line("• Message: " . substr($error['message'], 0, 100) . '...');
        $this->line("• Stacktrace lines: " . count($error['stacktrace']));
    }

    protected function buildAnalysisContext(array $error): string
    {
        $context = "# Laravel Error Analysis Request\n\n";
        $context .= "> This error was extracted from the application logs and needs analysis.\n\n";

        // Error summary
        $context .= "## Error Summary\n";
        $context .= "- **Timestamp**: {$error['timestamp']}\n";
        $context .= "- **Level**: {$error['level']}\n";
        $context .= "- **Application**: " . config('app.name') . "\n";
        $context .= "- **Environment**: " . app()->environment() . "\n";
        $context .= "- **URL**: " . config('app.url') . "\n\n";

        // Full error message
        $context .= "## Complete Error Message\n";
        $context .= "```\n{$error['message']}\n```\n\n";

        // Stacktrace
        if (!empty($error['stacktrace'])) {
            $context .= "## Stacktrace\n";
            $context .= "```\n" . implode("\n", $error['stacktrace']) . "\n```\n\n";
        }

        // System information
        $context .= "## System Information\n";
        $context .= "- **PHP Version**: " . PHP_VERSION . "\n";
        $context .= "- **Laravel Version**: " . app()->version() . "\n";
        $context .= "- **Database Driver**: " . config('database.default') . "\n";
        $context .= "- **Cache Driver**: " . config('cache.default') . "\n";
        $context .= "- **Queue Driver**: " . config('queue.default') . "\n";
        $context .= "- **Debug Mode**: " . (config('app.debug') ? 'Enabled' : 'Disabled') . "\n\n";

        // Application files involved
        $appFiles = $this->extractApplicationFiles($error);
        if (!empty($appFiles)) {
            $context .= "## Application Files Involved\n";
            foreach ($appFiles as $file) {
                $context .= "- `{$file}`\n";
            }
            $context .= "\n";
        }

        // Recent git history
        $context .= "## Recent Changes (Git Log)\n";
        $commits = $this->getRecentCommits();
        if (!empty($commits)) {
            foreach ($commits as $commit) {
                $context .= "- {$commit}\n";
            }
        } else {
            $context .= "- No git history available\n";
        }
        $context .= "\n";

        // Analysis request
        $context .= "## Analysis Request\n";
        $context .= "Please analyze this error and provide:\n\n";
        $context .= "1. **Root Cause**: What is causing this error?\n";
        $context .= "2. **Severity**: Critical/High/Medium/Low and why\n";
        $context .= "3. **Impact**: What functionality is affected?\n";
        $context .= "4. **Fix Instructions**: Step-by-step solution\n";

        if ($this->option('create-issue')) {
            $context .= "5. **GitHub Issue**: Format as a complete GitHub issue with title, labels, and body\n";
        }

        if ($this->option('fix')) {
            $context .= "6. **Immediate Fix**: If this can be fixed with artisan commands, provide them\n";
        }

        return $context;
    }

    protected function extractApplicationFiles(array $error): array
    {
        $files = [];
        $basePath = base_path();
        $appPath = app_path();

        // Extract from stacktrace
        foreach ($error['stacktrace'] as $line) {
            if (preg_match('/([\/\\\\\w\-\.]+\.php)[:\(](\d+)\)?/', $line, $matches)) {
                $file = $matches[1];
                if (str_contains($file, $appPath)) {
                    $files[] = str_replace($basePath . '/', '', $file) . ':' . $matches[2];
                }
            }
        }

        // Extract from error message
        if (preg_match_all('/(app\/[\w\/]+\.php)/', $error['message'], $matches)) {
            foreach ($matches[1] as $file) {
                $files[] = $file;
            }
        }

        return array_unique($files);
    }

    protected function getRecentCommits(): array
    {
        $result = Process::run('git log --oneline -5 2>/dev/null');

        if ($result->successful() && !empty(trim($result->output()))) {
            return explode("\n", trim($result->output()));
        }

        return [];
    }

    protected function showAnalysisInstructions(): void
    {
        $this->info("\n" . str_repeat('=', 60));
        $this->info("🎯 NEXT STEPS FOR CLAUDE CODE:");
        $this->info(str_repeat('=', 60));

        $this->comment("\n1. Read the error context:");
        $this->line("   Use: Read tool on storage/logs/current-error-analysis.md");

        $this->comment("\n2. Analyze the error and provide:");
        $this->line("   • Root cause analysis");
        $this->line("   • Severity assessment");
        $this->line("   • Impact analysis");
        $this->line("   • Step-by-step fix");

        if ($this->option('create-issue')) {
            $this->comment("\n3. Create GitHub issue:");
            $this->line("   • Use the gh CLI tool to create the issue");
            $this->line("   • Format: gh issue create --title \"[BUG] ...\" --body \"...\"");
        }

        if ($this->option('fix')) {
            $this->comment("\n4. Apply fixes:");
            $this->line("   • Use Bash tool to run any fix commands");
            $this->line("   • Test the fix by re-running the error scenario");
        }

        $this->info("\n💡 The error context file contains all the information needed for analysis!");
    }
}
