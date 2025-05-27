<?php

namespace App\Jobs;

use App\Models\GmailToken;
use App\Services\GmailStatsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoadGmailAccountStatsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 120; // 2 minutes timeout
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $accountId,
        public bool $forceRefresh = false
    ) {
        // Set queue name for better control
        $this->onQueue('gmail-stats');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Loading Gmail stats for account', ['account_id' => $this->accountId]);

        try {
            $account = GmailToken::find($this->accountId);

            if (!$account) {
                Log::warning('Gmail account not found', ['account_id' => $this->accountId]);
                return;
            }

            if ($account->status !== 'connected' || $account->isExpired()) {
                Log::info('Skipping stats for disconnected/expired account', [
                    'account_id' => $this->accountId,
                    'status'     => $account->status,
                    'expired'    => $account->isExpired()
                ]);
                return;
            }

            // Check cache unless force refresh
            $cacheKey = "gmail_stats_{$account->id}";
            if (!$this->forceRefresh && cache()->has($cacheKey)) {
                Log::info('Stats already cached, skipping', ['account_id' => $this->accountId]);
                return;
            }

            $gmailClient = $account->user->getGmailClientForAccount($account->gmail_email);

            if (!$gmailClient) {
                Log::error('Could not get Gmail client for account', ['account_id' => $this->accountId]);
                return;
            }

            // Use enhanced stats service if available, fallback to optimized method
            $stats = $this->fetchStatsWithService($account, $gmailClient);

            // Cache the results for 10 minutes (longer cache for background jobs)
            cache()->put($cacheKey, $stats, now()->addMinutes(10));

            // Update the account's last sync time
            $account->update(['last_sync_at' => now()]);

            Log::info('Gmail stats loaded successfully', [
                'account_id' => $this->accountId,
                'stats'      => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to load Gmail stats in background', [
                'account_id' => $this->accountId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString()
            ]);

            // Cache error state to prevent immediate retries
            $errorStats = [
                'unread_count' => '!',
                'today_count'  => '!',
                'labels_count' => '!',
                'error'        => 'Failed to load'
            ];

            cache()->put("gmail_stats_{$this->accountId}", $errorStats, now()->addMinutes(2));

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Gmail stats job failed permanently', [
            'account_id' => $this->accountId,
            'error'      => $exception->getMessage(),
            'attempts'   => $this->attempts()
        ]);

        // Set error state in cache
        $errorStats = [
            'unread_count' => '!',
            'today_count'  => '!',
            'labels_count' => '!',
            'error'        => 'Service unavailable'
        ];

        cache()->put("gmail_stats_{$this->accountId}", $errorStats, now()->addMinutes(5));
    }

    /**
     * Fetch stats using PR enhanced method or fallback method
     */
    private function fetchStatsWithService($account, $gmailClient): array
    {
        try {
            // Use the new getAccountStatistics method from PR #17
            $enhancedStats = $gmailClient->getAccountStatistics([
                'unread_limit'          => 1000,      // Get actual count up to 1000 unread
                'today_limit'           => 300,        // Get actual count up to 300 today
                'include_labels'        => true,
                'estimate_large_counts' => false, // User wants actual counts, not estimates
                'background_mode'       => true,   // Don't throw exceptions, return partial results
                'timeout'               => 120,           // Extend timeout for background processing
            ]);

            Log::info('PR #17 enhanced stats fetched', [
                'account_id'      => $account->id,
                'api_calls'       => $enhancedStats['api_calls_made'] ?? 'unknown',
                'partial_failure' => $enhancedStats['partial_failure'] ?? false,
                'stats'           => [
                    'unread' => $enhancedStats['unread_count'],
                    'today'  => $enhancedStats['today_count'],
                    'labels' => $enhancedStats['labels_count']
                ]
            ]);

            return [
                'unread_count'    => $enhancedStats['unread_count'],
                'today_count'     => $enhancedStats['today_count'],
                'labels_count'    => $enhancedStats['labels_count'],
                'api_calls_made'  => $enhancedStats['api_calls_made'] ?? 3,
                'last_updated'    => $enhancedStats['last_updated'] ?? now()->toISOString(),
                'partial_failure' => $enhancedStats['partial_failure'] ?? false
            ];

        } catch (\Exception $e) {
            Log::warning('PR #17 enhanced stats method failed, falling back to custom service', [
                'account_id' => $account->id,
                'error'      => $e->getMessage()
            ]);

            // Try our custom stats service as fallback
            try {
                $statsService = new GmailStatsService($gmailClient);

                $customStats = $statsService->getAccountStatistics([
                    'unread_max_fetch' => 1000,
                    'today_max_fetch'  => 300,
                    'include_labels'   => true
                ]);

                return [
                    'unread_count'   => $customStats['unread_count'],
                    'today_count'    => $customStats['today_count'],
                    'labels_count'   => $customStats['labels_count'],
                    'api_calls_made' => $customStats['api_calls_made'] ?? 3,
                    'last_updated'   => $customStats['last_updated'] ?? now()->toISOString()
                ];

            } catch (\Exception $customE) {
                Log::warning('Custom stats service also failed, using basic method', [
                    'account_id' => $account->id,
                    'error'      => $customE->getMessage()
                ]);

                // Final fallback to basic optimized method
                return $this->fetchStatsOptimized($gmailClient, $account);
            }
        }
    }

    /**
     * Fetch stats with optimized queries (fallback method)
     */
    private function fetchStatsOptimized($gmailClient, $account): array
    {
        $stats = [];

        // 1. Get actual unread count with pagination if needed
        try {
            $unreadCount = $this->getActualUnreadCount($gmailClient, 1000);
            $stats['unread_count'] = $unreadCount;

        } catch (\Exception $e) {
            Log::warning('Failed to fetch unread count', [
                'account_id' => $account->id,
                'error'      => $e->getMessage()
            ]);
            $stats['unread_count'] = '?';
        }

        // 2. Get actual today's messages count
        try {
            $todayCount = $this->getActualTodayCount($gmailClient, 300);
            $stats['today_count'] = $todayCount;

        } catch (\Exception $e) {
            Log::warning('Failed to fetch today count', [
                'account_id' => $account->id,
                'error'      => $e->getMessage()
            ]);
            $stats['today_count'] = '?';
        }

        // 3. Get labels count (usually fast)
        try {
            $labels = $gmailClient->listLabels();
            $stats['labels_count'] = is_countable($labels) ? count($labels) : 0;

        } catch (\Exception $e) {
            Log::warning('Failed to fetch labels count', [
                'account_id' => $account->id,
                'error'      => $e->getMessage()
            ]);
            $stats['labels_count'] = '?';
        }

        return $stats;
    }

    /**
     * Get actual unread count with pagination
     */
    private function getActualUnreadCount($gmailClient, int $maxCount): int
    {
        $totalCount = 0;
        $pageToken = null;
        $apiCalls = 0;

        do {
            $query = [
                'q'                => 'is:unread',
                'maxResults'       => 100,
                'includeSpamTrash' => false
            ];

            if ($pageToken) {
                $query['pageToken'] = $pageToken;
            }

            $response = $gmailClient->listMessages($query);
            $apiCalls++;

            if (is_countable($response)) {
                $totalCount += count($response);
            }

            // Get next page token if it exists
            $pageToken = $response->nextPageToken ?? null;

            // Safety limits
            if ($apiCalls >= 10 || $totalCount >= $maxCount) {
                break;
            }

        } while ($pageToken);

        return $totalCount;
    }

    /**
     * Get actual today's count with pagination
     */
    private function getActualTodayCount($gmailClient, int $maxCount): int
    {
        $totalCount = 0;
        $pageToken = null;
        $apiCalls = 0;
        $today = now()->format('Y/m/d');

        do {
            $query = [
                'q'                => "after:$today",
                'maxResults'       => 100,
                'includeSpamTrash' => false
            ];

            if ($pageToken) {
                $query['pageToken'] = $pageToken;
            }

            $response = $gmailClient->listMessages($query);
            $apiCalls++;

            if (is_countable($response)) {
                $totalCount += count($response);
            }

            $pageToken = $response->nextPageToken ?? null;

            // For today's messages, 3 API calls should be enough for most cases
            if ($apiCalls >= 3 || $totalCount >= $maxCount) {
                break;
            }

        } while ($pageToken);

        return $totalCount;
    }
}
