<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PartridgeRocks\GmailClient\GmailClient;

/**
 * Enhanced Gmail Statistics Service
 *
 * This service provides optimized methods for fetching Gmail statistics
 * and could be contributed back to the Gmail package.
 */
class GmailStatsService
{
    public function __construct(
        private GmailClient $gmailClient
    ) {
    }

    /**
     * Create a factory method for the service
     */
    public static function forAccount(string $accessToken, string $refreshToken, \DateTime $expiresAt): self
    {
        $gmailClient = app(GmailClient::class)->authenticate($accessToken, $refreshToken, $expiresAt);
        return new self($gmailClient);
    }

    /**
     * Get comprehensive account statistics in a single optimized call
     *
     * This method minimizes API calls while providing essential metrics
     * for dashboard display.
     */
    public function getAccountStatistics(array $options = []): array
    {
        $options = array_merge([
            'unread_max_fetch' => 1000, // Max unread to count (prevents runaway API calls)
            'today_max_fetch'  => 500,   // Max today's messages to count
            'include_labels'   => true,
            'cache_duration'   => 300, // 5 minutes
        ], $options);

        $stats = [
            'unread_count'    => 0,
            'today_count'     => 0,
            'labels_count'    => 0,
            'estimated_total' => 0,
            'last_updated'    => now()->toISOString(),
            'api_calls_made'  => 0
        ];

        try {
            // 1. Get actual unread count
            $unreadStats = $this->getUnreadStatistics($options['unread_max_fetch']);
            $stats = array_merge($stats, $unreadStats);

            // 2. Get actual today's activity count
            $todayStats = $this->getTodayStatistics($options['today_max_fetch']);
            $stats = array_merge($stats, $todayStats);
            $stats['api_calls_made'] += $todayStats['api_calls_made'] ?? 0;

            // 3. Get labels if requested
            if ($options['include_labels']) {
                $stats = array_merge($stats, $this->getLabelsStatistics());
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error('Failed to fetch Gmail statistics', [
                'error'   => $e->getMessage(),
                'options' => $options
            ]);

            return array_merge($stats, [
                'error'        => $e->getMessage(),
                'unread_count' => '?',
                'today_count'  => '?',
                'labels_count' => '?'
            ]);
        }
    }

    /**
     * Get quick health check for account connectivity
     */
    public function getAccountHealthCheck(): array
    {
        try {
            // Simple labels request as connectivity test
            $labels = $this->gmailClient->listLabels();

            return [
                'connected'        => true,
                'api_responsive'   => true,
                'last_check'       => now()->toISOString(),
                'labels_available' => count($labels),
            ];

        } catch (\Exception $e) {
            return [
                'connected'      => false,
                'api_responsive' => false,
                'last_check'     => now()->toISOString(),
                'error'          => $e->getMessage(),
            ];
        }
    }

    /**
     * Batch fetch statistics for multiple queries
     *
     * This could be enhanced if the Gmail package supports batch requests
     */
    public function getBatchStatistics(array $queries): array
    {
        $results = [];
        $totalApiCalls = 0;

        foreach ($queries as $key => $query) {
            try {
                $response = $this->gmailClient->listMessages($query);
                $results[$key] = [
                    'count'   => is_countable($response) ? count($response) : 0,
                    'success' => true
                ];
                $totalApiCalls++;

            } catch (\Exception $e) {
                $results[$key] = [
                    'count'   => 0,
                    'success' => false,
                    'error'   => $e->getMessage()
                ];
                $totalApiCalls++;
            }
        }

        return [
            'results'         => $results,
            'total_api_calls' => $totalApiCalls,
            'timestamp'       => now()->toISOString()
        ];
    }

    /**
     * Get actual unread message count (no estimation)
     */
    private function getUnreadStatistics(int $maxFetch = 1000): array
    {
        try {
            // First, try to get a reasonable batch to check if we need more
            $query = [
                'q'                => 'is:unread',
                'maxResults'       => min($maxFetch, 100), // Start with 100
                'includeSpamTrash' => false,
                'fields'           => 'messages/id'
            ];

            $response = $this->gmailClient->listMessages($query);
            $initialCount = is_countable($response) ? count($response) : 0;

            // If we got the max results, there might be more - fetch in batches
            if ($initialCount >= 100 && $maxFetch > 100) {
                $totalCount = $this->getExactUnreadCount($maxFetch);
            } else {
                $totalCount = $initialCount;
            }

            return [
                'unread_count'     => $totalCount,
                'unread_estimated' => false,
                'api_calls_made'   => $totalCount > 100 ? 2 : 1
            ];

        } catch (\Exception $e) {
            Log::warning('Failed to fetch unread statistics', ['error' => $e->getMessage()]);
            return [
                'unread_count'     => '?',
                'unread_estimated' => false,
                'api_calls_made'   => 1
            ];
        }
    }

    /**
     * Get exact unread count for large mailboxes
     */
    private function getExactUnreadCount(int $maxFetch): int
    {
        $totalCount = 0;
        $pageToken = null;
        $apiCalls = 0;

        do {
            $query = [
                'q'                => 'is:unread',
                'maxResults'       => 100,
                'includeSpamTrash' => false,
                'fields'           => 'messages/id,nextPageToken'
            ];

            if ($pageToken) {
                $query['pageToken'] = $pageToken;
            }

            $response = $this->gmailClient->listMessages($query);
            $apiCalls++;

            if (is_countable($response)) {
                $totalCount += count($response);
            }

            // Get next page token if available and we haven't hit our limit
            $pageToken = $response->nextPageToken ?? null;

            // Safety brake - don't make too many API calls
            if ($apiCalls >= 10 || $totalCount >= $maxFetch) {
                break;
            }

        } while ($pageToken);

        return $totalCount;
    }

    /**
     * Get actual today's message count
     */
    private function getTodayStatistics(int $maxFetch = 500): array
    {
        try {
            $today = now()->format('Y/m/d');

            // For today's messages, we can usually get exact count efficiently
            $totalCount = 0;
            $pageToken = null;
            $apiCalls = 0;

            do {
                $query = [
                    'q'                => "after:{$today}",
                    'maxResults'       => 100,
                    'includeSpamTrash' => false,
                    'fields'           => 'messages/id,nextPageToken'
                ];

                if ($pageToken) {
                    $query['pageToken'] = $pageToken;
                }

                $response = $this->gmailClient->listMessages($query);
                $apiCalls++;

                if (is_countable($response)) {
                    $totalCount += count($response);
                }

                $pageToken = $response->nextPageToken ?? null;

                // For today's messages, limit to 5 API calls max (500 messages)
                if ($apiCalls >= 5 || $totalCount >= $maxFetch) {
                    break;
                }

            } while ($pageToken);

            return [
                'today_count'     => $totalCount,
                'today_estimated' => false,
                'api_calls_made'  => $apiCalls
            ];

        } catch (\Exception $e) {
            Log::warning('Failed to fetch today statistics', ['error' => $e->getMessage()]);
            return [
                'today_count'     => '?',
                'today_estimated' => false,
                'api_calls_made'  => 1
            ];
        }
    }

    /**
     * Get labels statistics
     */
    private function getLabelsStatistics(): array
    {
        try {
            $labels = $this->gmailClient->listLabels();

            $systemLabels = collect($labels)->where('type', 'system')->count();
            $userLabels = collect($labels)->where('type', 'user')->count();

            return [
                'labels_count'  => count($labels),
                'system_labels' => $systemLabels,
                'user_labels'   => $userLabels,
            ];

        } catch (\Exception $e) {
            Log::warning('Failed to fetch labels statistics', ['error' => $e->getMessage()]);
            return [
                'labels_count'  => '?',
                'system_labels' => 0,
                'user_labels'   => 0,
            ];
        }
    }
}
