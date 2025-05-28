<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class AutomatedLogMonitoring extends Command
{
    protected $signature = 'logs:monitor
                            {--interval=15 : Check interval in minutes}
                            {--threshold=3 : Minimum occurrences to trigger issue}
                            {--validate : Validate errors still occur before creating issues}
                            {--dry-run : Show what would be done without creating issues}
                            {--use-ai : Use Claude AI for error analysis (recommended)}';

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
        $useAI = $this->option('use-ai');

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
            $this->processErrorPattern($patternKey, $pattern, $validate, $dryRun, $useAI);
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
            '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/'                           => 'TIMESTAMP',
            '/\d+\.\d+\.\d+\.\d+/'                                            => 'IP_ADDRESS',
            '/\b\d+\b/'                                                       => 'NUMBER',
            '/[a-f0-9]{32,}/'                                                 => 'HASH',
            '/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i' => 'UUID',
            '/user_id[=:\s]*\d+/'                                             => 'user_id=ID',
            '/\/tmp\/[a-zA-Z0-9]+/'                                           => '/tmp/TMPFILE'
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

    protected function processErrorPattern(string $key, array $pattern, bool $validate, bool $dryRun, bool $useAI = false): void
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

        // Use Claude AI analysis if enabled
        if ($useAI) {
            $aiAnalysis = $this->analyzeWithClaude($pattern);
            if ($aiAnalysis) {
                $this->processAIAnalysis($key, $pattern, $aiAnalysis, $dryRun);
                return;
            }
            $this->comment("⚠️  AI analysis failed, falling back to rule-based analysis");
        }

        // Check severity threshold (rule-based fallback)
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
                return !Schema::hasTable($tableName);
            } catch (\Exception $e) {
                Log::warning(
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

        $result = Process::run([
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

    protected function analyzeWithClaude(array $pattern): ?array
    {
        $this->comment("🧠 Analyzing with Claude AI...");

        try {
            // Create context file for Claude
            $contextFile = $this->createClaudeContextFile($pattern);

            // Build Claude analysis prompt
            $prompt = $this->buildClaudeAnalysisPrompt();

            // Execute Claude CLI command
            $result = Process::timeout(120)->run(
                "cat {$contextFile} | claude -p " . escapeshellarg($prompt)
            );

            // Clean up temp file
            unlink($contextFile);

            if ($result->successful()) {
                $response = trim($result->output());

                // Claude sometimes wraps JSON in markdown code blocks
                if (str_contains($response, '```json')) {
                    // Extract JSON from markdown code block
                    preg_match('/```json\s*(.*?)\s*```/s', $response, $matches);
                    if (!empty($matches[1])) {
                        $response = trim($matches[1]);
                    }
                } elseif (str_contains($response, '```')) {
                    // Extract from generic code block
                    preg_match('/```\s*(.*?)\s*```/s', $response, $matches);
                    if (!empty($matches[1])) {
                        $response = trim($matches[1]);
                    }
                }

                // Try to parse JSON response
                $analysis = json_decode($response, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->info("✅ Claude analysis completed");
                    return $analysis;
                } else {
                    $this->comment("⚠️  Claude response was not valid JSON");
                    $this->line("Response: " . substr($response, 0, 200) . '...');
                }
            } else {
                $this->comment("⚠️  Claude command failed: " . $result->errorOutput());
            }
        } catch (\Exception $e) {
            $this->comment("⚠️  Claude analysis error: " . $e->getMessage());
        }

        return null;
    }

    protected function createClaudeContextFile(array $pattern): string
    {
        $sample = $pattern['sample_error'];

        $context = "# Laravel Error Analysis Request\n\n";

        // Error pattern summary
        $context .= "## Error Pattern Summary\n";
        $context .= "- **Occurrences**: {$pattern['count']} times\n";
        $context .= "- **Time Range**: {$pattern['first_seen']} → {$pattern['last_seen']}\n";
        $context .= "- **Level**: {$sample['level']}\n\n";

        // Complete error message
        $context .= "## Error Message\n";
        $context .= "```\n{$sample['message']}\n```\n\n";

        // Stacktrace if available
        if (!empty($sample['stacktrace'])) {
            $context .= "## Stacktrace (Top 10 lines)\n";
            $context .= "```\n" . implode("\n", array_slice($sample['stacktrace'], 0, 10)) . "\n```\n\n";
        }

        // System context
        $context .= "## System Information\n";
        $context .= "- **Application**: " . config('app.name') . "\n";
        $context .= "- **Environment**: " . app()->environment() . "\n";
        $context .= "- **Laravel Version**: " . app()->version() . "\n";
        $context .= "- **PHP Version**: " . PHP_VERSION . "\n";
        $context .= "- **Database**: " . config('database.default') . "\n\n";

        // Recent occurrences
        $context .= "## Recent Occurrences\n";
        foreach (array_slice($pattern['instances'], -5) as $timestamp) {
            $context .= "- {$timestamp}\n";
        }
        $context .= "\n";

        // Analysis context
        $context .= "## Analysis Context\n";
        $context .= "This error pattern was detected by the automated log monitoring system. ";
        $context .= "It has occurred {$pattern['count']} times in the monitoring period. ";
        $context .= "Please analyze whether this requires immediate attention and a GitHub issue.\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'claude_error_analysis_');
        file_put_contents($tempFile, $context);

        return $tempFile;
    }

    protected function buildClaudeAnalysisPrompt(): string
    {
        return "Analyze this Laravel error and respond in JSON format:

{
  \"severity\": \"critical|high|medium|low\",
  \"confidence\": \"high|medium|low\",
  \"root_cause\": \"Brief explanation of what's causing this error\",
  \"impact\": \"What functionality is affected\",
  \"should_create_issue\": true|false,
  \"issue_title\": \"GitHub issue title if should_create_issue is true\",
  \"fix_commands\": [\"php artisan migrate\", \"composer install\"],
  \"investigation_steps\": [\"Check database connection\", \"Verify permissions\"],
  \"category\": \"database|queue|mail|auth|api|application|console\",
  \"is_transient\": true|false,
  \"reasoning\": \"Explain your analysis and decision\"
}

Guidelines:
- Consider the frequency and timing of occurrences
- Assess if this is likely a temporary vs persistent issue
- Only recommend creating GitHub issues for problems that need developer attention
- Provide specific, actionable fix commands when possible
- Be conservative - avoid creating issues for minor or resolved problems
- Consider the application context and error patterns

Respond ONLY with valid JSON, no additional text.";
    }

    protected function processAIAnalysis(string $key, array $pattern, array $analysis, bool $dryRun): void
    {
        $this->info("🧠 Claude AI Analysis Results:");
        $this->line("  Severity: " . strtoupper($analysis['severity'] ?? 'unknown'));
        $this->line("  Confidence: " . strtoupper($analysis['confidence'] ?? 'unknown'));
        $this->line("  Category: " . ucfirst($analysis['category'] ?? 'unknown'));
        $this->line("  Is Transient: " . ($analysis['is_transient'] ? 'Yes' : 'No'));
        $this->line("  Should Create Issue: " . ($analysis['should_create_issue'] ? 'Yes' : 'No'));

        if (!empty($analysis['reasoning'])) {
            $this->comment("  Reasoning: " . $analysis['reasoning']);
        }

        // Show fix commands if provided
        if (!empty($analysis['fix_commands'])) {
            $this->info("  Suggested Fixes:");
            foreach ($analysis['fix_commands'] as $command) {
                $this->line("    • {$command}");
            }
        }

        // Show investigation steps if provided
        if (!empty($analysis['investigation_steps'])) {
            $this->info("  Investigation Steps:");
            foreach ($analysis['investigation_steps'] as $step) {
                $this->line("    • {$step}");
            }
        }

        // Create issue if Claude recommends it
        if ($analysis['should_create_issue']) {
            if ($dryRun) {
                $this->warn("\n🧪 DRY RUN - Would create GitHub issue:");
                $this->line("Title: " . ($analysis['issue_title'] ?? 'AI-Analyzed Error'));
            } else {
                $this->createAIGitHubIssue($key, $pattern, $analysis);
            }
        } else {
            $this->comment("✅ Claude determined no GitHub issue needed");
            if ($analysis['is_transient']) {
                $this->markPatternAsResolved($key);
            }
        }
    }

    protected function createAIGitHubIssue(string $key, array $pattern, array $analysis): void
    {
        $title = $analysis['issue_title'] ?? $this->generateIssueTitle($pattern);
        $body = $this->generateAIIssueBody($pattern, $analysis);

        $this->info("📝 Creating AI-analyzed GitHub issue...");

        $result = Process::run([
            'php', 'artisan', 'issues:create-as-bot',
            $title,
            $body
        ]);

        if ($result->successful()) {
            $issueUrl = trim($result->output());
            $this->info("✅ AI-analyzed issue created: {$issueUrl}");

            // Track that we created an issue for this pattern
            $this->markIssueCreated($key, $issueUrl);
        } else {
            $this->error("❌ Failed to create issue: " . $result->errorOutput());
        }
    }

    protected function generateAIIssueBody(array $pattern, array $analysis): string
    {
        $sample = $pattern['sample_error'];

        $body = "## 🧠 AI-Analyzed Error Report\n\n";

        // Claude's analysis summary
        $body .= "**Claude AI Analysis**:\n";
        $body .= "- **Severity**: " . strtoupper($analysis['severity']) . "\n";
        $body .= "- **Confidence**: " . strtoupper($analysis['confidence']) . "\n";
        $body .= "- **Category**: " . ucfirst($analysis['category']) . "\n";
        $body .= "- **Is Transient**: " . ($analysis['is_transient'] ? 'Yes' : 'No') . "\n\n";

        // Root cause and impact
        if (!empty($analysis['root_cause'])) {
            $body .= "**Root Cause**: {$analysis['root_cause']}\n\n";
        }

        if (!empty($analysis['impact'])) {
            $body .= "**Impact**: {$analysis['impact']}\n\n";
        }

        // Claude's reasoning
        if (!empty($analysis['reasoning'])) {
            $body .= "**AI Reasoning**: {$analysis['reasoning']}\n\n";
        }

        // Error pattern details
        $body .= "## Error Pattern Details\n";
        $body .= "- **Occurrences**: {$pattern['count']} times\n";
        $body .= "- **Time Range**: {$pattern['first_seen']} → {$pattern['last_seen']}\n";
        $body .= "- **Level**: {$sample['level']}\n\n";

        // Error message
        $body .= "## Error Message\n";
        $body .= "```\n{$sample['message']}\n```\n\n";

        // Recommended fixes
        if (!empty($analysis['fix_commands'])) {
            $body .= "## 🔧 AI-Recommended Fixes\n";
            foreach ($analysis['fix_commands'] as $command) {
                $body .= "```bash\n{$command}\n```\n";
            }
            $body .= "\n";
        }

        // Investigation steps
        if (!empty($analysis['investigation_steps'])) {
            $body .= "## 🔍 Investigation Steps\n";
            foreach ($analysis['investigation_steps'] as $step) {
                $body .= "- {$step}\n";
            }
            $body .= "\n";
        }

        // Recent occurrences
        $body .= "## Recent Occurrences\n";
        foreach (array_slice($pattern['instances'], -10) as $timestamp) {
            $body .= "- {$timestamp}\n";
        }

        $body .= "\n## Environment\n";
        $body .= "- **Application**: " . config('app.name') . "\n";
        $body .= "- **Environment**: " . app()->environment() . "\n";
        $body .= "- **Laravel Version**: " . app()->version() . "\n";
        $body .= "- **Analysis Time**: " . now()->toDateTimeString() . "\n\n";

        $body .= "---\n";
        $body .= "*This issue was analyzed by Claude AI and automatically generated by the Laravel Log Monitoring System*";

        return $body;
    }
}
