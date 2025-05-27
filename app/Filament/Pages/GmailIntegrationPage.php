<?php

namespace App\Filament\Pages;

use App\Jobs\LoadGmailAccountStatsJob;
use App\Models\GmailToken;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use PartridgeRocks\GmailClient\Facades\GmailClient;

class GmailIntegrationPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.gmail-account-dashboard';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Accounts';

    protected static ?int $navigationSort = 90;

    // Properties for multi-account support
    public $gmailAccounts = [];
    public $primaryAccount = null;
    public $totalUnreadCount = 0;
    public $oauthUrl = '';

    public function getHeading(): string
    {
        return 'Gmail Account Dashboard';
    }

    public function getSubheading(): string
    {
        return 'Manage multiple Gmail accounts and integrations';
    }

    public function mount(): void
    {
        $this->loadGmailAccounts();
        $this->generateOAuthUrl();

        // Load stats asynchronously after initial load
        $this->dispatch('load-stats-after-mount');
    }

    /**
     * Auto-load stats after page mount
     */
    public function loadStatsAfterMount(): void
    {
        $this->loadAllStats();
    }

    /**
     * Generate OAuth URL for Gmail authentication
     */
    public function generateOAuthUrl(): void
    {
        try {
            $this->oauthUrl = GmailClient::getAuthorizationUrl(
                config('gmail-client.redirect_uri'),
                config('gmail-client.scopes')
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate OAuth URL', [
                'error'        => $e->getMessage(),
                'redirect_uri' => config('gmail-client.redirect_uri'),
                'scopes'       => config('gmail-client.scopes'),
            ]);
            $this->oauthUrl = '#';
        }
    }

    /**
     * Load all Gmail accounts for the user with cached stats
     */
    public function loadGmailAccounts(): void
    {
        $user = auth()->user();

        // Get all Gmail accounts
        $accounts = $user->gmailAccounts()->with('user')->get();

        $this->gmailAccounts = $accounts->map(function ($account) {
            // Try to get cached stats first
            $cacheKey = "gmail_stats_{$account->id}";
            $cachedStats = cache()->get($cacheKey);

            $stats = $cachedStats ?: [
                'unread_count' => '---',
                'today_count'  => '---',
                'labels_count' => '---'
            ];

            return [
                'id'              => $account->id,
                'gmail_email'     => $account->gmail_email,
                'account_name'    => $account->account_name,
                'display_name'    => $account->display_name,
                'is_primary'      => $account->is_primary,
                'status'          => $account->status,
                'avatar'          => $account->avatar,
                'is_avatar_image' => $account->is_avatar_image,
                'last_sync'       => $account->last_sync_format,
                'expires_at'      => $account->expires_at?->format('M d, Y'),
                'account_info'    => $account->account_info,
                'unread_count'    => $stats['unread_count'],
                'today_count'     => $stats['today_count'],
                'labels_count'    => $stats['labels_count'],
                'stats_loading'   => false,
            ];
        })->toArray();

        // Get primary account
        $this->primaryAccount = collect($this->gmailAccounts)->firstWhere('is_primary', true);

        Log::info('Gmail accounts loaded with cached stats', [
            'user_id'             => $user->id,
            'total_accounts'      => count($this->gmailAccounts),
            'primary_account'     => $this->primaryAccount['gmail_email'] ?? 'None',
            'accounts_with_stats' => count(array_filter($this->gmailAccounts, fn ($acc) => $acc['unread_count'] !== '---'))
        ]);
    }

    /**
     * Check if the user has any authenticated Gmail accounts
     */
    public function hasAnyAuthenticatedAccounts(): bool
    {
        return auth()->user()->hasValidGmailToken();
    }

    /**
     * Check if the user is authenticated with Gmail (backwards compatibility)
     */
    public function isAuthenticated(): bool
    {
        return $this->hasAnyAuthenticatedAccounts();
    }

    /**
     * Get count of connected accounts
     */
    public function getConnectedAccountsCount(): int
    {
        return count(array_filter($this->gmailAccounts, fn ($account) => $account['status'] === 'connected'));
    }

    /**
     * Test account health using new PR #17 functionality
     */
    public function testAccountHealth(int $accountId): void
    {
        try {
            $account = GmailToken::findOrFail($accountId);

            if ($account->user_id !== auth()->id()) {
                $this->addError('account_health', 'Unauthorized access to account.');
                return;
            }

            $gmailClient = $account->user->getGmailClientForAccount($account->gmail_email);

            if (!$gmailClient) {
                $this->addError('account_health', 'Could not connect to Gmail client.');
                return;
            }

            // Test the new getAccountHealth method from PR #17
            $health = $gmailClient->getAccountHealth();

            $message = "Health Check Results:\n";
            $message .= "Status: " . ($health['status'] ?? 'unknown') . "\n";
            $message .= "Connected: " . ($health['connected'] ? 'Yes' : 'No') . "\n";
            $message .= "Last Success: " . ($health['last_successful_call'] ?? 'Never') . "\n";

            if (!empty($health['errors'])) {
                $message .= "Errors: " . implode(', ', $health['errors']);
            }

            $this->notification()
                ->title('Account Health Check')
                ->body($message)
                ->success()
                ->send();

            Log::info('Account health check completed', [
                'account_id' => $accountId,
                'health'     => $health
            ]);

        } catch (\Exception $e) {
            $this->addError('account_health', 'Health check failed: ' . $e->getMessage());

            Log::error('Account health check failed', [
                'account_id' => $accountId,
                'error'      => $e->getMessage()
            ]);
        }
    }

    /**
     * Check for updated stats from cache and refresh UI
     */
    public function checkForUpdatedStats(): void
    {
        $updated = false;

        foreach ($this->gmailAccounts as $index => $account) {
            if ($account['status'] === 'connected' &&
                in_array($account['unread_count'], ['...', '---'])) {

                $cacheKey = "gmail_stats_{$account['id']}";
                $cachedStats = cache()->get($cacheKey);

                if ($cachedStats) {
                    $this->updateAccountInArray($account['id'], [
                        'unread_count'  => $cachedStats['unread_count'],
                        'today_count'   => $cachedStats['today_count'],
                        'labels_count'  => $cachedStats['labels_count'],
                        'stats_loading' => false,
                    ]);
                    $updated = true;
                }
            }
        }

        if ($updated) {
            Log::info('Gmail stats updated from cache');
        }
    }

    /**
     * Set an account as primary
     */
    public function setPrimaryAccount(int $accountId): void
    {
        try {
            $account = GmailToken::findOrFail($accountId);

            // Ensure the account belongs to the current user
            if ($account->user_id !== auth()->id()) {
                throw new Exception('Unauthorized account access');
            }

            $account->setPrimary();
            $this->loadGmailAccounts();

            Notification::make()
                ->title('Primary Account Updated')
                ->body("'{$account->display_name}' is now your primary Gmail account")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to update primary account: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Load stats for a specific account (now uses background job)
     */
    public function loadStatsForAccount(int $accountId): void
    {
        try {
            $account = GmailToken::findOrFail($accountId);

            // Ensure the account belongs to the current user
            if ($account->user_id !== auth()->id()) {
                return;
            }

            // Set loading state immediately
            $this->updateAccountInArray($accountId, ['stats_loading' => true]);

            // Check if we have cached stats first
            $cacheKey = "gmail_stats_{$accountId}";
            $cachedStats = cache()->get($cacheKey);

            if ($cachedStats) {
                // Use cached stats immediately
                $this->updateAccountInArray($accountId, [
                    'unread_count'  => $cachedStats['unread_count'],
                    'today_count'   => $cachedStats['today_count'],
                    'labels_count'  => $cachedStats['labels_count'],
                    'stats_loading' => false,
                ]);
                return;
            }

            // Dispatch background job for fresh stats
            LoadGmailAccountStatsJob::dispatch($accountId)
                ->onQueue('gmail-stats');

            // Set temporary loading message
            $this->updateAccountInArray($accountId, [
                'unread_count'  => '...',
                'today_count'   => '...',
                'labels_count'  => '...',
                'stats_loading' => false, // Job is running in background
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to initiate stats loading', [
                'account_id' => $accountId,
                'error'      => $e->getMessage(),
            ]);

            // Set error state
            $this->updateAccountInArray($accountId, [
                'unread_count'  => '!',
                'today_count'   => '!',
                'labels_count'  => '!',
                'stats_loading' => false,
            ]);
        }
    }

    /**
     * Load stats for all accounts asynchronously
     */
    public function loadAllStats(): void
    {
        $dispatchedJobs = 0;

        foreach ($this->gmailAccounts as $account) {
            if ($account['status'] === 'connected') {
                // Dispatch background job for each account
                LoadGmailAccountStatsJob::dispatch($account['id'])
                    ->onQueue('gmail-stats')
                    ->delay(now()->addSeconds($dispatchedJobs * 2)); // Stagger requests

                $dispatchedJobs++;
            }
        }

        if ($dispatchedJobs > 0) {
            Notification::make()
                ->title('Loading Stats')
                ->body("Started background loading for {$dispatchedJobs} account(s). Stats will update automatically.")
                ->success()
                ->send();
        }
    }

    /**
     * Refresh stats for a specific account (force refresh)
     */
    public function refreshAccountStats(int $accountId): void
    {
        try {
            $account = GmailToken::findOrFail($accountId);

            // Ensure the account belongs to the current user
            if ($account->user_id !== auth()->id()) {
                throw new Exception('Unauthorized account access');
            }

            // Clear cache to force fresh data
            cache()->forget("gmail_stats_{$accountId}");

            // Set loading state
            $this->updateAccountInArray($accountId, [
                'stats_loading' => true,
                'unread_count'  => '...',
                'today_count'   => '...',
                'labels_count'  => '...'
            ]);

            // Dispatch job with force refresh
            LoadGmailAccountStatsJob::dispatch($accountId, true)
                ->onQueue('gmail-stats');

            Notification::make()
                ->title('Refreshing Stats')
                ->body("Refreshing statistics for '{$account->display_name}' in the background")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to refresh account stats: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Disconnect a specific Gmail account
     */
    public function disconnectAccount(int $accountId): void
    {
        try {
            $account = GmailToken::findOrFail($accountId);

            // Ensure the account belongs to the current user
            if ($account->user_id !== auth()->id()) {
                throw new Exception('Unauthorized account access');
            }

            $accountName = $account->display_name;
            $account->delete();
            $this->loadGmailAccounts();

            Notification::make()
                ->title('Account Disconnected')
                ->body("'{$accountName}' has been disconnected successfully")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to disconnect account: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Get account summary for dashboard
     */
    public function getAccountSummary(): array
    {
        $connected = $this->getConnectedAccountsCount();
        $total = count($this->gmailAccounts);
        $primary = $this->primaryAccount;

        return [
            'total_accounts'     => $total,
            'connected_accounts' => $connected,
            'primary_account'    => $primary ? $primary['gmail_email'] : 'None',
            'has_expired'        => $total - $connected,
        ];
    }

    /**
     * Get Gmail stats for a specific account (optimized)
     */
    public function getAccountStats($account): array
    {
        try {
            if ($account->status !== 'connected' || $account->isExpired()) {
                return [
                    'unread_count' => 0,
                    'today_count'  => 0,
                    'labels_count' => 0,
                ];
            }

            // Check cache first
            $cacheKey = "gmail_stats_{$account->id}";
            $cachedStats = cache()->get($cacheKey);

            if ($cachedStats) {
                Log::info('Using cached Gmail stats', ['account_id' => $account->id]);
                return $cachedStats;
            }

            $gmailClient = auth()->user()->getGmailClientForAccount($account->gmail_email);

            if (!$gmailClient) {
                return [
                    'unread_count' => 0,
                    'today_count'  => 0,
                    'labels_count' => 0,
                ];
            }

            // Use lighter-weight queries with minimal results
            $stats = [];

            // Get actual unread count (no estimation)
            try {
                $stats['unread_count'] = $this->getActualUnreadCount($gmailClient, 500);
            } catch (\Exception $e) {
                Log::warning('Failed to fetch unread messages', ['error' => $e->getMessage()]);
                $stats['unread_count'] = '?';
            }

            // Get actual today's messages count
            try {
                $stats['today_count'] = $this->getActualTodayCount($gmailClient, 200);
            } catch (\Exception $e) {
                Log::warning('Failed to fetch today messages', ['error' => $e->getMessage()]);
                $stats['today_count'] = '?';
            }

            // Get labels count (this is typically fast)
            try {
                $labels = $gmailClient->listLabels();
                $stats['labels_count'] = is_countable($labels) ? count($labels) : 0;
            } catch (\Exception $e) {
                Log::warning('Failed to fetch labels', ['error' => $e->getMessage()]);
                $stats['labels_count'] = '?';
            }

            // Cache the results for 5 minutes
            cache()->put($cacheKey, $stats, now()->addMinutes(5));

            Log::info('Gmail stats fetched and cached', [
                'account_id' => $account->id,
                'stats'      => $stats
            ]);

            return $stats;

        } catch (\Exception $e) {
            Log::error('Failed to fetch Gmail stats', [
                'account_email' => $account->gmail_email,
                'error'         => $e->getMessage(),
            ]);

            return [
                'unread_count' => '!',
                'today_count'  => '!',
                'labels_count' => '!',
            ];
        }
    }

    /**
     * Get messages from Gmail - for demonstration purposes
     */
    public function getMessages()
    {
        if (!$this->isAuthenticated()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();

            return [];
        }

        try {
            // Get the Gmail client from the user
            $gmailClient = auth()->user()->getGmailClient();

            // List recent messages
            return $gmailClient->listMessages(['maxResults' => 5]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error fetching messages')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return [];
        }
    }

    /**
     * Get the authentication URL
     */
    protected function authenticateAction(): Action
    {
        return Action::make('authenticate')
            ->label('Authenticate with Gmail')
            ->color('primary')
            ->url(function (): string {
                return GmailClient::getAuthorizationUrl(
                    config('gmail-client.redirect_uri'),
                    config('gmail-client.scopes')
                );
            });
    }

    /**
     * List messages action
     */
    protected function listMessagesAction(): Action
    {
        return Action::make('listMessages')
            ->label('List Recent Messages')
            ->color('success')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-messages-page');
            })
            ->disabled(fn (): bool => !$this->isAuthenticated());
    }

    /**
     * List labels action
     */
    protected function listLabelsAction(): Action
    {
        return Action::make('listLabels')
            ->label('List Labels')
            ->color('warning')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-labels-page');
            })
            ->disabled(fn (): bool => !$this->isAuthenticated());
    }

    /**
     * Revoke Gmail authentication
     */
    protected function revokeAction(): Action
    {
        return Action::make('revoke')
            ->label('Disconnect Gmail')
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->modalHeading('Disconnect Gmail')
            ->modalDescription('Are you sure you want to disconnect your Gmail account? You will need to re-authenticate to access emails.')
            ->modalSubmitActionLabel('Yes, disconnect')
            ->action(function () {
                $user = auth()->user();
                if ($user->gmailToken) {
                    $user->gmailToken->delete();
                }

                Notification::make()
                    ->title('Gmail Disconnected')
                    ->body('Your Gmail account has been successfully disconnected.')
                    ->success()
                    ->send();

                // Refresh the page state
                $this->loadGmailAccounts();
            })
            ->visible(fn (): bool => $this->isAuthenticated());
    }

    /**
     * Quick action to view messages
     */
    protected function viewMessagesAction(): Action
    {
        return Action::make('viewMessages')
            ->label('View Messages')
            ->color('primary')
            ->icon('heroicon-o-envelope-open')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-messages-page');
            })
            ->visible(fn (): bool => $this->isAuthenticated());
    }

    /**
     * Add new Gmail account action
     */
    protected function addAccountAction(): Action
    {
        return Action::make('addAccount')
            ->label('Add Gmail Account')
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->url(function (): string {
                return GmailClient::getAuthorizationUrl(
                    config('gmail-client.redirect_uri'),
                    config('gmail-client.scopes')
                );
            });
    }

    /**
     * View all messages action (enhanced for multi-account)
     */
    protected function viewAllMessagesAction(): Action
    {
        return Action::make('viewAllMessages')
            ->label('View Messages')
            ->color('primary')
            ->icon('heroicon-o-envelope-open')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-messages-page');
            })
            ->visible(fn (): bool => $this->hasAnyAuthenticatedAccounts());
    }

    /**
     * Sync all accounts action
     */
    protected function syncAllAccountsAction(): Action
    {
        return Action::make('syncAllAccounts')
            ->label('Refresh Stats')
            ->color('warning')
            ->icon('heroicon-o-arrow-path')
            ->action(function () {
                // Load stats for all connected accounts in background
                $this->loadAllStats();

                Notification::make()
                    ->title('Refreshing Stats')
                    ->body('Gmail account statistics are being refreshed in the background')
                    ->success()
                    ->send();
            })
            ->visible(fn (): bool => $this->getConnectedAccountsCount() > 0);
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            $this->addAccountAction(),
        ];

        if ($this->hasAnyAuthenticatedAccounts()) {
            $actions[] = $this->viewAllMessagesAction();
            $actions[] = $this->syncAllAccountsAction();
        }

        return $actions;
    }

    /**
     * Helper to update a specific account in the gmailAccounts array
     */
    private function updateAccountInArray(int $accountId, array $updates): void
    {
        $this->gmailAccounts = collect($this->gmailAccounts)->map(function ($account) use ($accountId, $updates) {
            if ($account['id'] === $accountId) {
                return array_merge($account, $updates);
            }
            return $account;
        })->toArray();
    }

    /**
     * Get actual unread count with pagination (fallback for direct calls)
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

            $pageToken = $response->nextPageToken ?? null;

            // Safety limits for direct calls
            if ($apiCalls >= 5 || $totalCount >= $maxCount) {
                break;
            }

        } while ($pageToken);

        return $totalCount;
    }

    /**
     * Get actual today's count with pagination (fallback for direct calls)
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

            // For today's messages, 2 API calls should be enough for most cases
            if ($apiCalls >= 2 || $totalCount >= $maxCount) {
                break;
            }

        } while ($pageToken);

        return $totalCount;
    }
}
