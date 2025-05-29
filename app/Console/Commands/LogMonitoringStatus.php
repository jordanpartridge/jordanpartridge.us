<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LogMonitoringStatus extends Command
{
    protected $signature = 'logs:monitor-status
                            {--clear-cache : Clear monitoring cache}';

    protected $description = 'Show current log monitoring status and statistics';

    public function handle()
    {
        if ($this->option('clear-cache')) {
            Cache::forget('log_monitor_recent_issues');
            Cache::forget('log_monitor_resolved_patterns');
            Cache::forget('log_monitor_last_run');
            $this->info('✅ Monitoring cache cleared');
            return Command::SUCCESS;
        }

        $this->showMonitoringStatus();
        return Command::SUCCESS;
    }

    protected function showMonitoringStatus(): void
    {
        $this->info('📊 LOG MONITORING STATUS');
        $this->line(str_repeat('=', 50));

        // Last run information
        $lastRun = Cache::get('log_monitor_last_run');
        if ($lastRun) {
            $lastRunTime = Carbon::parse($lastRun['timestamp']);
            $this->info("\n🕐 Last Monitoring Run:");
            $this->line("  Time: {$lastRunTime->format('Y-m-d H:i:s')} ({$lastRunTime->diffForHumans()})");
            $this->line("  Patterns Found: {$lastRun['patterns_found']}");
            $this->line("  Total Errors: {$lastRun['total_errors']}");
        } else {
            $this->warn("\n⚠️  No monitoring runs detected yet");
        }

        // Recent issues created
        $recentIssues = Cache::get('log_monitor_recent_issues', []);
        if (!empty($recentIssues)) {
            $this->info("\n📝 Recent Issues Created:");
            $this->table(
                ['Pattern', 'Created', 'Issue URL'],
                collect($recentIssues)->map(function ($issue, $key) {
                    $createdAt = Carbon::parse($issue['created_at']);
                    return [
                        substr($key, 0, 30) . '...',
                        $createdAt->diffForHumans(),
                        $issue['issue_url']
                    ];
                })->take(10)->toArray()
            );

            $this->line("Total Issues Tracked: " . count($recentIssues));
        } else {
            $this->comment("\n✅ No issues created recently");
        }

        // Resolved patterns
        $resolvedPatterns = Cache::get('log_monitor_resolved_patterns', []);
        if (!empty($resolvedPatterns)) {
            $this->info("\n✅ Resolved Patterns:");
            foreach (array_slice($resolvedPatterns, -5, null, true) as $pattern => $resolvedAt) {
                $resolvedTime = Carbon::parse($resolvedAt);
                $this->line("  • " . substr($pattern, 0, 40) . "... (resolved {$resolvedTime->diffForHumans()})");
            }
            $this->line("Total Resolved: " . count($resolvedPatterns));
        }

        // Current configuration
        $this->info("\n⚙️  Current Configuration:");
        $this->line("  Check Interval: Every 15 minutes");
        $this->line("  Error Threshold: 3 occurrences minimum");
        $this->line("  Validation: Enabled (checks if errors still occur)");
        $this->line("  Severity Filter: High/Critical, or Medium with 10+ occurrences");
        $this->line("  Duplicate Prevention: 24 hours");

        // Next scheduled run
        $this->info("\n⏰ Next Scheduled Run:");
        $now = Carbon::now();
        $nextRun = $now->copy()->addMinutes(15 - ($now->minute % 15))->second(0);
        $this->line("  {$nextRun->format('Y-m-d H:i:s')} ({$nextRun->diffForHumans()})");

        // Quick stats
        $this->showQuickStats();
    }

    protected function showQuickStats(): void
    {
        $this->info("\n📈 Quick Statistics:");

        try {
            // Count recent log entries
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $recentErrors = $this->countRecentErrors();
                $this->line("  Errors in last hour: {$recentErrors['hour']}");
                $this->line("  Errors in last 24 hours: {$recentErrors['day']}");
            } else {
                $this->line("  Log file not found");
            }
        } catch (\Exception $e) {
            $this->line("  Could not read log statistics");
        }

        // Cache efficiency
        $recentIssues = Cache::get('log_monitor_recent_issues', []);
        $resolvedPatterns = Cache::get('log_monitor_resolved_patterns', []);

        $this->line("  Issues in cache: " . count($recentIssues));
        $this->line("  Resolved patterns: " . count($resolvedPatterns));
    }

    protected function countRecentErrors(): array
    {
        $logPath = storage_path('logs/laravel.log');
        $hourAgo = Carbon::now()->subHour();
        $dayAgo = Carbon::now()->subDay();

        $hourCount = 0;
        $dayCount = 0;

        $handle = fopen($logPath, 'r');
        if ($handle) {
            // Read from the end of the file
            fseek($handle, -8192, SEEK_END); // Start near the end
            $content = fread($handle, 8192);
            fclose($handle);

            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(ERROR|CRITICAL|ALERT|EMERGENCY):/', $line, $matches)) {
                    $timestamp = Carbon::parse($matches[1]);

                    if ($timestamp->gt($hourAgo)) {
                        $hourCount++;
                    }
                    if ($timestamp->gt($dayAgo)) {
                        $dayCount++;
                    }
                }
            }
        }

        return ['hour' => $hourCount, 'day' => $dayCount];
    }
}
