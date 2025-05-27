<x-filament::page>
    <style>
        .account-dashboard {
            display: grid;
            gap: 24px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border: 1px solid #4c1d95;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-elevated, #FFFFFF);
            border: 1px solid var(--border-color, #D1D1D6);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.2s;
        }

        .dark .stat-card {
            background: #1a1a24;
            border-color: #2a2a3a;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dark .stat-card:hover {
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary, #000000);
            margin-bottom: 8px;
        }

        .dark .stat-value {
            color: #ffffff;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary, #6B7280);
            font-weight: 500;
        }

        .dark .stat-label {
            color: #9ca3af;
        }

        .accounts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .account-card {
            background: var(--bg-elevated, #FFFFFF);
            border: 1px solid var(--border-color, #D1D1D6);
            border-radius: 12px;
            padding: 24px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .dark .account-card {
            background: #1a1a24;
            border-color: #2a2a3a;
        }

        .account-card.primary {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .account-card.expired {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }

        .account-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .account-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            color: white;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .account-info h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary, #000000);
            margin-bottom: 4px;
        }

        .dark .account-info h3 {
            color: #ffffff;
        }

        .account-email {
            font-size: 14px;
            color: var(--text-secondary, #6B7280);
        }

        .dark .account-email {
            color: #9ca3af;
        }

        .account-badges {
            display: flex;
            gap: 8px;
            margin: 16px 0;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge.primary {
            background: #6366f1;
            color: white;
        }

        .badge.connected {
            background: #10b981;
            color: white;
        }

        .badge.expired {
            background: #ef4444;
            color: white;
        }

        .account-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 16px 0;
        }

        .account-stat {
            text-align: center;
            padding: 12px;
            background: var(--bg-secondary, #F2F2F7);
            border-radius: 8px;
        }

        .dark .account-stat {
            background: #252530;
        }

        .account-stat-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary, #000000);
        }

        .dark .account-stat-value {
            color: #ffffff;
        }

        .account-stat-label {
            font-size: 11px;
            color: var(--text-secondary, #6B7280);
            margin-top: 4px;
        }

        .dark .account-stat-label {
            color: #9ca3af;
        }

        .account-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .account-btn {
            flex: 1;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-align: center;
            text-decoration: none;
        }

        .account-btn.primary {
            background: #6366f1;
            color: white;
        }

        .account-btn.primary:hover {
            background: #5558e3;
        }

        .account-btn.secondary {
            background: transparent;
            color: var(--text-secondary, #6B7280);
            border-color: var(--border-color, #D1D1D6);
        }

        .dark .account-btn.secondary {
            color: #9ca3af;
            border-color: #374151;
        }

        .account-btn.secondary:hover {
            background: var(--bg-secondary, #F2F2F7);
            color: var(--text-primary, #000000);
        }

        .dark .account-btn.secondary:hover {
            background: #374151;
            color: #ffffff;
        }

        .account-btn.danger {
            background: transparent;
            color: #ef4444;
            border-color: #ef4444;
        }

        .account-btn.danger:hover {
            background: #ef4444;
            color: white;
        }

        .add-account-card {
            border: 2px dashed var(--border-color, #D1D1D6);
            background: var(--bg-secondary, #F2F2F7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 24px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .dark .add-account-card {
            border-color: #374151;
            background: #252530;
        }

        .add-account-card:hover {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }

        .add-account-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .add-account-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary, #000000);
            margin-bottom: 8px;
        }

        .dark .add-account-title {
            color: #ffffff;
        }

        .add-account-description {
            font-size: 14px;
            color: var(--text-secondary, #6B7280);
            margin-bottom: 16px;
        }

        .dark .add-account-description {
            color: #9ca3af;
        }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary, #000000);
            margin-bottom: 12px;
        }

        .dark .empty-state h3 {
            color: #ffffff;
        }

        .empty-state p {
            font-size: 16px;
            color: var(--text-secondary, #6B7280);
            margin-bottom: 24px;
        }

        .dark .empty-state p {
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .accounts-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .account-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2 style="font-size: 24px; font-weight: 600; color: #ffffff; margin-bottom: 8px;">
            Gmail Account Management
        </h2>
        <p style="color: #c7d2fe; margin-bottom: 20px;">
            Connect and manage multiple Gmail accounts for enhanced email intelligence and CRM integration.
        </p>

        <!-- Quick Stats -->
        <div class="dashboard-stats">
            @php
                $summary = $this->getAccountSummary();
            @endphp
            <div class="stat-card">
                <div class="stat-value">{{ $summary['total_accounts'] }}</div>
                <div class="stat-label">Total Accounts</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $summary['connected_accounts'] }}</div>
                <div class="stat-label">Connected</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $summary['has_expired'] }}</div>
                <div class="stat-label">Need Reconnection</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $summary['primary_account'] !== 'None' ? '1' : '0' }}</div>
                <div class="stat-label">Primary Set</div>
            </div>
        </div>
    </div>

    <!-- Account Cards -->
    @if (count($gmailAccounts) > 0)
        <div class="accounts-grid">
            @foreach ($gmailAccounts as $account)
                <div class="account-card {{ $account['is_primary'] ? 'primary' : '' }} {{ $account['status'] === 'expired' ? 'expired' : '' }}">
                    <!-- Primary Badge -->
                    @if ($account['is_primary'])
                        <div style="position: absolute; top: 12px; right: 12px;">
                            <div class="badge primary">Primary</div>
                        </div>
                    @endif

                    <!-- Account Header -->
                    <div class="account-header">
                        <div class="account-avatar">
                            @if ($account['is_avatar_image'] ?? false)
                                <img src="{{ $account['avatar'] }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ $account['avatar'] }}
                            @endif
                        </div>
                        <div class="account-info">
                            <h3>{{ $account['account_name'] ?: 'Gmail Account' }}</h3>
                            <div class="account-email">{{ $account['gmail_email'] ?: 'No email set' }}</div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="account-badges">
                        <div class="badge {{ $account['status'] === 'connected' ? 'connected' : 'expired' }}">
                            {{ ucfirst($account['status']) }}
                        </div>
                        @if ($account['expires_at'])
                            <div class="badge" style="background: #374151; color: #d1d5db;">
                                Expires {{ $account['expires_at'] }}
                            </div>
                        @endif
                    </div>

                    <!-- Account Stats -->
                    <div class="account-stats">
                        <div class="account-stat">
                            <div class="account-stat-value">
                                @if ($account['stats_loading'])
                                    <div wire:loading.class="opacity-50" wire:target="loadStatsForAccount({{ $account['id'] }})">
                                        <span class="animate-spin">⟳</span>
                                    </div>
                                @elseif (is_numeric($account['unread_count']))
                                    {{ number_format($account['unread_count']) }}
                                @else
                                    {{ $account['unread_count'] }}
                                @endif
                            </div>
                            <div class="account-stat-label">Unread</div>
                        </div>
                        <div class="account-stat">
                            <div class="account-stat-value">
                                @if ($account['stats_loading'])
                                    <div wire:loading.class="opacity-50" wire:target="loadStatsForAccount({{ $account['id'] }})">
                                        <span class="animate-spin">⟳</span>
                                    </div>
                                @elseif (is_numeric($account['today_count']))
                                    {{ number_format($account['today_count']) }}
                                @else
                                    {{ $account['today_count'] }}
                                @endif
                            </div>
                            <div class="account-stat-label">Today</div>
                        </div>
                        <div class="account-stat">
                            <div class="account-stat-value">
                                @if ($account['stats_loading'])
                                    <div wire:loading.class="opacity-50" wire:target="loadStatsForAccount({{ $account['id'] }})">
                                        <span class="animate-spin">⟳</span>
                                    </div>
                                @elseif (is_numeric($account['labels_count']))
                                    {{ number_format($account['labels_count']) }}
                                @else
                                    {{ $account['labels_count'] }}
                                @endif
                            </div>
                            <div class="account-stat-label">Labels</div>
                        </div>
                    </div>

                    <!-- Last Sync -->
                    @if ($account['last_sync'])
                        <div style="font-size: 12px; color: var(--text-tertiary, #9CA3AF); margin: 12px 0;">
                            Last sync: {{ $account['last_sync'] }}
                        </div>
                    @endif

                    <!-- Account Actions -->
                    <div class="account-actions">
                        @if ($account['status'] === 'connected')
                            <a href="{{ route('filament.admin.pages.gmail-messages-page') }}" class="account-btn primary">
                                View Messages
                            </a>
                            <button wire:click="loadStatsForAccount({{ $account['id'] }})" class="account-btn secondary">
                                <span wire:loading.remove wire:target="loadStatsForAccount({{ $account['id'] }})">Refresh</span>
                                <span wire:loading wire:target="loadStatsForAccount({{ $account['id'] }})">Loading...</span>
                            </button>
                            @if (!$account['is_primary'])
                                <button wire:click="setPrimaryAccount({{ $account['id'] }})" class="account-btn secondary">
                                    Set Primary
                                </button>
                            @endif
                        @else
                            <a href="{{ $oauthUrl }}"
                               class="account-btn primary">
                                Reconnect
                            </a>
                        @endif
                        <button wire:click="disconnectAccount({{ $account['id'] }})"
                                onclick="return confirm('Are you sure you want to disconnect this account?')"
                                class="account-btn danger">
                            Disconnect
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Add Account Card -->
            <a href="{{ $oauthUrl }}" class="add-account-card" style="text-decoration: none; color: inherit;">
                <div class="add-account-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <h3 class="add-account-title">Add Gmail Account</h3>
                <p class="add-account-description">Connect personal, business, or client accounts</p>
                <button class="account-btn primary" style="max-width: 200px;" type="button">
                    Connect Account
                </button>
            </a>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h3>No Gmail Accounts Connected</h3>
            <p>Connect your first Gmail account to start managing emails with CRM intelligence.</p>
            <a href="{{ $oauthUrl }}"
               class="account-btn primary" style="display: inline-block; max-width: 200px;">
                Connect First Account
            </a>
        </div>
    @endif

    <!-- Auto-load stats after page renders -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a moment for the page to fully render, then load stats
            setTimeout(function() {
                @foreach ($gmailAccounts as $account)
                    @if ($account['status'] === 'connected')
                        // Load stats for account {{ $account['id'] }}
                        setTimeout(function() {
                            @this.call('loadStatsForAccount', {{ $account['id'] }});
                        }, {{ $loop->index * 500 }}); // Stagger requests by 500ms
                    @endif
                @endforeach
            }, 100);
        });
    </script>
</x-filament::page>