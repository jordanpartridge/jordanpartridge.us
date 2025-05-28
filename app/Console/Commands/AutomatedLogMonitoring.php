<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AutomatedLogMonitoring extends Command
{
    protected $signature = 'logs:monitor 
                            {--interval=15 : Check interval in minutes}
                            {--threshold=3 : Minimum occurrences to trigger issue}
                            {--validate : Validate errors still occur before creating issues}
                            {--dry-run : Show what would be done without creating issues}';

    protected $description = 'Automated log monitoring with intelligent issue creation';

    protected array $errorPatterns = [];
    protected array $validatedErrors = [];

    public function handle()
    {
        $this->info('🤖 Starting automated log monitoring...');

        $interval = $this->option('interval');
        $threshold = $this->option('threshold');
        $validate = $this->option('validate');
        $dryRun = $this->option('dry-run');

        // Get recent errors
        $errors = $this->getRecentErrors($interval);

        if (empty($errors)) {
            $this->info('✅ No errors found in the last ' . $interval . ' minutes');
            return Command::SUCCESS;
        }

        // Group and analyze error patterns
        $patterns = $this->analyzeErrorPatterns($errors, $threshold);

        if (empty($patterns)) {
            $this->info('✅ No error patterns exceed threshold of ' . $threshold . ' occurrences');
            return Command::SUCCESS;
        }

        $this->info(sprintf('📊 Found %d error patterns exceeding threshold', count($patterns)));

        // Process each pattern
        foreach ($patterns as $patternKey => $pattern) {
            $this->processErrorPattern($patternKey, $pattern, $validate, $dryRun);
        }

        // Store monitoring state
        $this->updateMonitoringState($patterns);

        return Command::SUCCESS;
    }

    protected function getRecentErrors(int $minutes): array
    {
        $logPath = storage_path('logs/laravel.log');
        $cutoffTime = Carbon::now()->subMinutes($minutes);
        $errors = [];

        if (!file_exists($logPath)) {
            return [];
        }

        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines); // Start from most recent

        $currentEntry = [];
        $inError = false;

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(ERROR|CRITICAL|ALERT|EMERGENCY):/', $line, $matches)) {
                $timestamp = Carbon::parse($matches[1]);

                // If we've gone past our cutoff time, stop processing
                if ($timestamp->lt($cutoffTime)) {
                    break;
                }

                // Save previous entry if we were building one
                if ($inError && !empty($currentEntry)) {
                    $errors[] = $currentEntry;
                }

                // Start new entry
                $currentEntry = [
                    'timestamp'   => $matches[1],
                    'level'       => $matches[2],
                    'message'     => trim(substr($line, strpos($line, ':') + 1)),
                    'stacktrace'  => [],
                    'pattern_key' => ''
                ];
                $inError = true;
            } elseif ($inError && !empty($currentEntry)) {
                if (str_starts_with($line, '#') || str_contains($line, ' at ')) {
                    $currentEntry['stacktrace'][] = $line;
                } else {
                    $currentEntry['message'] .= "\n" . $line;
                }
            }
        }

        // Don't forget the last entry
        if ($inError && !empty($currentEntry)) {
            $errors[] = $currentEntry;
        }

        return $errors;
    }

    protected function analyzeErrorPatterns(array $errors, int $threshold): array
    {
        $patterns = [];

        foreach ($errors as $error) {
            $key = $this->generatePatternKey($error);
            $error['pattern_key'] = $key;

            if (!isset($patterns[$key])) {
                $patterns[$key] = [
                    'count'        => 0,
                    'first_seen'   => $error['timestamp'],
                    'last_seen'    => $error['timestamp'],
                    'sample_error' => $error,
                    'severity'     => $this->assessSeverity($error),
                    'category'     => $this->categorizeError($error),
                    'instances'    => []
                ];
            }

            $patterns[$key]['count']++;
            $patterns[$key]['last_seen'] = $error['timestamp'];
            $patterns[$key]['instances'][] = $error['timestamp'];
        }

        // Filter by threshold
        return array_filter($patterns, function ($pattern) use ($threshold) {
            return $pattern['count'] >= $threshold;
        });
    }

    protected function generatePatternKey(array $error): string
    {
        $message = $error['message'];

        // Normalize the message to create a pattern
        $patterns = [
            '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/' => 'TIMESTAMP',
            '/\d+\.\d+\.\d+\.\d+/'                  => 'IP_ADDRESS',
            '/\b\d+\b/'                             => 'NUMBER',
            '/[a-f0-9]{32,}/'                       => 'HASH',
            '/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i' => 'UUID',
            '/user_id[=:\s]*\d+/'                   => 'user_id=ID',
            '/\/tmp\/[a-zA-Z0-9]+/'                 => '/tmp/TMPFILE'
        ];

        foreach ($patterns as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        // Get the exception class if present
        if (preg_match('/(\w+Exception)/', $message, $matches)) {
            return $matches[1] . ':' . substr(md5($message), 0, 12);
        }

        return 'ERROR:' . substr(md5($message), 0, 12);
    }

    protected function processErrorPattern(string $key, array $pattern, bool $validate, bool $dryRun): void
    {
        $this->line("\n" . str_repeat('─', 60));
        $this->info("Processing: {$key}");
        $this->line("Count: {$pattern['count']} | Severity: {$pattern['severity']} | Category: {$pattern['category']}");

        // Check if we've already created an issue for this pattern recently
        if ($this->hasRecentIssue($key)) {
            $this->comment("⏭️  Skipping - Issue already created recently for this pattern");
            return;
        }

        // Validate the error still occurs if requested
        if ($validate) {
            if (!$this->validateErrorStillOccurs($pattern)) {
                $this->comment("✅ Skipping - Error appears to be resolved");
                $this->markPatternAsResolved($key);
                return;
            }
        }

        // Check severity threshold
        if (!$this->meetsIssueSeverity($pattern)) {
            $this->comment("ℹ️  Skipping - Below severity threshold for automatic issue creation");
            return;
        }

        if ($dryRun) {
            $this->warn("🧪 DRY RUN - Would create GitHub issue:");
            $this->showProposedIssue($pattern);
        } else {
            $this->createGitHubIssue($key, $pattern);
        }
    }

    protected function hasRecentIssue(string $patternKey): bool
    {
        $recentIssues = Cache::get('log_monitor_recent_issues', []);
        $cutoff = Carbon::now()->subHours(24); // Don't create duplicate issues within 24 hours

        if (isset($recentIssues[$patternKey])) {
            $lastIssueTime = Carbon::parse($recentIssues[$patternKey]['created_at']);
            return $lastIssueTime->gt($cutoff);
        }

        return false;
    }

    protected function validateErrorStillOccurs(array $pattern): bool
    {
        // Check if the underlying issue still exists
        $sampleError = $pattern['sample_error'];

        // For database errors, try to reproduce the issue
        if (str_contains($sampleError['message'], 'Table') && str_contains($sampleError['message'], "doesn't exist")) {
            return $this->validateTableExists($sampleError);
        }

        // For class/method errors, check if files exist
        if (str_contains($sampleError['message'], 'Class') && str_contains($sampleError['message'], 'not found')) {
            return $this->validateClassExists($sampleError);
        }

        // For most recent occurrences within last 5 minutes, consider it active
        $lastOccurrence = Carbon::parse($pattern['last_seen']);
        return $lastOccurrence->gt(Carbon::now()->subMinutes(5));
    }

    protected function validateTableExists(array $error): bool
    {
        if (preg_match('/Table \'[^.]+\.([^\']+)\' doesn\'t exist/', $error['message'], $matches)) {
            $tableName = $matches[1];
            try {
                return !\Illuminate\Support\Facades\Schema::hasTable($tableName);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning(
                    "Unable to validate table existence: " . $e->getMessage()
                );
                return false; // Skip issue creation if we can't validate
            }
        }
        return true;
    }

    protected function validateClassExists(array $error): bool
    {
        if (preg_match('/Class \'([^\']+)\' not found/', $error['message'], $matches)) {
            $className = $matches[1];
            return !class_exists($className);
        }
        return true;
    }

    protected function assessSeverity(array $error): string
    {
        $message = strtolower($error['message']);
        $level = strtolower($error['level']);

        // Critical patterns
        if ($level === 'emergency' || $level === 'alert' ||
            str_contains($message, 'fatal') ||
            str_contains($message, 'segmentation fault') ||
            str_contains($message, 'out of memory')) {
            return 'critical';
        }

        // High severity
        if ($level === 'critical' ||
            str_contains($message, 'database') ||
            str_contains($message, 'connection') ||
            str_contains($message, 'authentication') ||
            str_contains($message, 'security')) {
            return 'high';
        }

        // Medium severity
        if ($level === 'error') {
            return 'medium';
        }

        return 'low';
    }

    protected function categorizeError(array $error): string
    {
        $message = strtolower($error['message']);

        if (str_contains($message, 'database') || str_contains($message, 'sql') || str_contains($message, 'mysql')) {
            return 'database';
        }
        if (str_contains($message, 'queue') || str_contains($message, 'job')) {
            return 'queue';
        }
        if (str_contains($message, 'mail') || str_contains($message, 'email')) {
            return 'mail';
        }
        if (str_contains($message, 'auth') || str_contains($message, 'login')) {
            return 'authentication';
        }
        if (str_contains($message, 'api') || str_contains($message, 'http') || str_contains($message, 'curl')) {
            return 'api';
        }

        return 'application';
    }

    protected function meetsIssueSeverity(array $pattern): bool
    {
        // Only create issues for high/critical errors, or medium errors with high frequency
        if (in_array($pattern['severity'], ['critical', 'high'])) {
            return true;
        }

        if ($pattern['severity'] === 'medium' && $pattern['count'] >= 10) {
            return true;
        }

        return false;
    }

    protected function showProposedIssue(array $pattern): void
    {
        $title = $this->generateIssueTitle($pattern);
        $this->line("Title: {$title}");
        $this->line("Severity: {$pattern['severity']}");
        $this->line("Category: {$pattern['category']}");
        $this->line("Occurrences: {$pattern['count']}");
        $this->line("Time Range: {$pattern['first_seen']} → {$pattern['last_seen']}");
    }

    protected function createGitHubIssue(string $key, array $pattern): void
    {
        $title = $this->generateIssueTitle($pattern);
        $body = $this->generateIssueBody($pattern);

        $this->info("📝 Creating GitHub issue...");

        $result = \Illuminate\Support\Facades\Process::run([
            'php', 'artisan', 'issues:create-as-bot',
            $title,
            $body
        ]);

        if ($result->successful()) {
            $issueUrl = trim($result->output());
            $this->info("✅ Issue created: {$issueUrl}");

            // Track that we created an issue for this pattern
            $this->markIssueCreated($key, $issueUrl);
        } else {
            $this->error("❌ Failed to create issue: " . $result->errorOutput());
        }
    }

    protected function generateIssueTitle(array $pattern): string
    {
        $severity = strtoupper($pattern['severity']);
        $category = ucfirst($pattern['category']);

        $message = $pattern['sample_error']['message'];

        // Extract key information for title
        if (str_contains($message, "doesn't exist")) {
            if (preg_match('/Table \'[^.]+\.([^\']+)\' doesn\'t exist/', $message, $matches)) {
                return "[{$severity}] Missing database table: {$matches[1]}";
            }
        }

        if (str_contains($message, 'Class') && str_contains($message, 'not found')) {
            if (preg_match('/Class \'([^\']+)\' not found/', $message, $matches)) {
                return "[{$severity}] Missing class: " . basename(str_replace('\\', '/', $matches[1]));
            }
        }

        // Generic title based on category
        return "[{$severity}] {$category} Error - {$pattern['count']} occurrences";
    }

    protected function generateIssueBody(array $pattern): string
    {
        $sample = $pattern['sample_error'];

        return "## Automated Error Detection Alert

**Error Pattern Detected**: {$pattern['count']} occurrences in monitoring period
**Severity**: {$pattern['severity']}
**Category**: {$pattern['category']}
**Time Range**: {$pattern['first_seen']} → {$pattern['last_seen']}

## Error Details
```
{$sample['message']}
```

## Occurrence Timeline
" . implode("\n", array_map(fn ($time) => "- {$time}", array_slice($pattern['instances'], -10))) . "

## Analysis
This error pattern was automatically detected by the log monitoring system and has exceeded the threshold for automatic issue creation.

## Recommended Actions
1. Investigate the root cause of this error pattern
2. Check if this is a new issue or recurring problem  
3. Implement appropriate fixes based on error category
4. Monitor for resolution

## Environment
- **Detection Time**: " . now()->toDateTimeString() . "
- **Laravel Version**: " . app()->version() . "
- **Environment**: " . app()->environment() . "

---
*This issue was automatically generated by the Laravel Log Monitoring System*";
    }

    protected function markIssueCreated(string $key, string $issueUrl): void
    {
        $recentIssues = Cache::get('log_monitor_recent_issues', []);
        $recentIssues[$key] = [
            'created_at'    => now()->toDateTimeString(),
            'issue_url'     => $issueUrl,
            'pattern_count' => $this->errorPatterns[$key]['count'] ?? 0
        ];

        // Keep only last 100 issues to prevent cache bloat
        if (count($recentIssues) > 100) {
            $recentIssues = array_slice($recentIssues, -100, null, true);
        }

        Cache::put('log_monitor_recent_issues', $recentIssues, now()->addDays(7));
    }

    protected function markPatternAsResolved(string $key): void
    {
        $resolvedPatterns = Cache::get('log_monitor_resolved_patterns', []);
        $resolvedPatterns[$key] = now()->toDateTimeString();

        Cache::put('log_monitor_resolved_patterns', $resolvedPatterns, now()->addDays(7));
    }

    protected function updateMonitoringState(array $patterns): void
    {
        Cache::put('log_monitor_last_run', [
            'timestamp'      => now()->toDateTimeString(),
            'patterns_found' => count($patterns),
            'total_errors'   => array_sum(array_column($patterns, 'count'))
        ], now()->addHours(24));
    }
}
