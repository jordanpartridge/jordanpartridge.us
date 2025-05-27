<x-filament::page>
    <style>
        /* Modern Dark Mode Label System */
        :root {
            --label-bg-primary: #FFFFFF;
            --label-bg-secondary: #F8F9FA;
            --label-bg-tertiary: #F1F3F4;
            --label-border: #E1E5E9;
            --label-text-primary: #202124;
            --label-text-secondary: #5F6368;
            --label-text-tertiary: #80868B;
            --label-hover-bg: #F8F9FA;
            --label-active-bg: #E8F0FE;
            --system-blue: #1A73E8;
            --system-green: #34A853;
            --system-red: #EA4335;
            --system-yellow: #FBBC04;
            --system-purple: #9C27B0;
            --system-orange: #FF8C00;
        }

        .dark {
            --label-bg-primary: #1F1F1F;
            --label-bg-secondary: #2D2D2D;
            --label-bg-tertiary: #383838;
            --label-border: #444746;
            --label-text-primary: #E8EAED;
            --label-text-secondary: #9AA0A6;
            --label-text-tertiary: #5F6368;
            --label-hover-bg: #2D2D2D;
            --label-active-bg: #1E3A8A;
        }

        [class*="dark"] {
            --label-bg-primary: #1F1F1F;
            --label-bg-secondary: #2D2D2D;
            --label-bg-tertiary: #383838;
            --label-border: #444746;
            --label-text-primary: #E8EAED;
            --label-text-secondary: #9AA0A6;
            --label-text-tertiary: #5F6368;
            --label-hover-bg: #2D2D2D;
            --label-active-bg: #1E3A8A;
        }

        .labels-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .label-card {
            background: var(--label-bg-primary);
            border: 1px solid var(--label-border);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .label-card:hover {
            background: var(--label-hover-bg);
            border-color: var(--system-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .label-card.system {
            border-left: 4px solid var(--system-blue);
        }

        .label-card.user {
            border-left: 4px solid var(--system-green);
        }

        .label-header {
            display: flex;
            justify-content: between;
            align-items: start;
            margin-bottom: 12px;
        }

        .label-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 600;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
        }

        .label-icon.system {
            background: linear-gradient(135deg, var(--system-blue), #1557B0);
        }

        .label-icon.user {
            background: linear-gradient(135deg, var(--system-green), #2E7D32);
        }

        .label-icon.inbox {
            background: linear-gradient(135deg, var(--system-blue), #1557B0);
        }

        .label-icon.sent {
            background: linear-gradient(135deg, var(--system-green), #2E7D32);
        }

        .label-icon.draft {
            background: linear-gradient(135deg, var(--system-yellow), #F57F17);
        }

        .label-icon.spam {
            background: linear-gradient(135deg, var(--system-red), #C62828);
        }

        .label-icon.trash {
            background: linear-gradient(135deg, #757575, #424242);
        }

        .label-icon.important {
            background: linear-gradient(135deg, var(--system-orange), #E65100);
        }

        .label-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--label-text-primary);
            margin: 0 0 4px 0;
            line-height: 1.3;
        }

        .label-id {
            font-size: 11px;
            color: var(--label-text-tertiary);
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            background: var(--label-bg-tertiary);
            padding: 2px 6px;
            border-radius: 4px;
            margin: 0;
        }

        .label-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 16px;
        }

        .stat-item {
            background: var(--label-bg-secondary);
            padding: 8px 12px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: 700;
            color: var(--label-text-primary);
            line-height: 1;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 10px;
            color: var(--label-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .unread-badge {
            background: var(--system-red);
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin: 32px 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--label-border);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--label-text-primary);
            margin: 0;
        }

        .section-count {
            background: var(--label-bg-secondary);
            color: var(--label-text-secondary);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: 12px;
        }

        .filter-tabs {
            display: flex;
            background: var(--label-bg-secondary);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            gap: 4px;
        }

        .filter-tab {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: var(--label-text-secondary);
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-tab.active {
            background: var(--system-blue);
            color: white;
            box-shadow: 0 2px 8px rgba(26, 115, 232, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--label-text-secondary);
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            background: var(--label-bg-secondary);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--label-text-tertiary);
        }

        .view-messages-btn {
            margin-top: 12px;
            padding: 8px 16px;
            background: var(--system-blue);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0;
            transform: translateY(4px);
        }

        .label-card:hover .view-messages-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .view-messages-btn:hover {
            background: #1557B0;
            transform: translateY(-1px);
        }

        /* Search Form Styling */
        .search-section {
            margin-bottom: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .labels-container {
                padding: 16px;
            }

            .label-card {
                padding: 16px;
            }

            .filter-tabs {
                flex-direction: column;
            }

            .filter-tab {
                padding: 10px 16px;
            }
        }
    </style>

    <div class="labels-container">
        <!-- Search Form -->
        <div class="search-section">
            {{ $this->form }}
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab {{ $selectedLabelType === 'all' ? 'active' : '' }}"
                    wire:click="selectLabelType('all')">
                All Labels ({{ count($systemLabels) + count($userLabels) }})
            </button>
            <button class="filter-tab {{ $selectedLabelType === 'system' ? 'active' : '' }}"
                    wire:click="selectLabelType('system')">
                System ({{ count($systemLabels) }})
            </button>
            <button class="filter-tab {{ $selectedLabelType === 'user' ? 'active' : '' }}"
                    wire:click="selectLabelType('user')">
                Custom ({{ count($userLabels) }})
            </button>
        </div>

        @if ($selectedLabelType === 'all' || $selectedLabelType === 'system')
            <!-- System Labels Section -->
            @if (count($systemLabels) > 0)
                <div class="section-header">
                    <h2 class="section-title">System Labels</h2>
                    <span class="section-count">{{ count($systemLabels) }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($systemLabels as $label)
                        <div class="label-card system" wire:click="viewLabelMessages('{{ $label['id'] }}', '{{ $label['name'] }}')">
                            <div class="label-header">
                                <div class="label-icon system {{ strtolower($label['name']) }}">
                                    {{ strtoupper(substr($label['name'], 0, 2)) }}
                                </div>
                                <div style="flex: 1;">
                                    <h3 class="label-name">{{ $label['name'] }}</h3>
                                    <p class="label-id">{{ $label['id'] }}</p>
                                </div>
                                @if ($label['messagesUnread'] > 0)
                                    <div class="unread-badge">{{ $label['messagesUnread'] }}</div>
                                @endif
                            </div>

                            <div class="label-stats">
                                <div class="stat-item">
                                    <div class="stat-number">{{ number_format($label['messagesTotal']) }}</div>
                                    <div class="stat-label">Messages</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ number_format($label['messagesUnread']) }}</div>
                                    <div class="stat-label">Unread</div>
                                </div>
                            </div>

                            <button class="view-messages-btn">
                                View Messages →
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        @if ($selectedLabelType === 'all' || $selectedLabelType === 'user')
            <!-- User Labels Section -->
            @if (count($userLabels) > 0)
                <div class="section-header">
                    <h2 class="section-title">Custom Labels</h2>
                    <span class="section-count">{{ count($userLabels) }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($userLabels as $label)
                        <div class="label-card user" wire:click="viewLabelMessages('{{ $label['id'] }}', '{{ $label['name'] }}')">
                            <div class="label-header">
                                <div class="label-icon user">
                                    {{ strtoupper(substr($label['name'], 0, 2)) }}
                                </div>
                                <div style="flex: 1;">
                                    <h3 class="label-name">{{ $label['name'] }}</h3>
                                    <p class="label-id">{{ $label['id'] }}</p>
                                </div>
                                @if ($label['messagesUnread'] > 0)
                                    <div class="unread-badge">{{ $label['messagesUnread'] }}</div>
                                @endif
                            </div>

                            <div class="label-stats">
                                <div class="stat-item">
                                    <div class="stat-number">{{ number_format($label['messagesTotal']) }}</div>
                                    <div class="stat-label">Messages</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ number_format($label['messagesUnread']) }}</div>
                                    <div class="stat-label">Unread</div>
                                </div>
                            </div>

                            <button class="view-messages-btn">
                                View Messages →
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Empty State -->
        @if (count($systemLabels) === 0 && count($userLabels) === 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 style="font-size: 16px; font-weight: 600; color: var(--label-text-primary); margin: 0 0 8px 0;">
                    @if (!empty($searchTerm))
                        No labels found for "{{ $searchTerm }}"
                    @else
                        No labels found
                    @endif
                </h3>
                <p style="font-size: 14px; color: var(--label-text-secondary); margin: 0;">
                    @if (!empty($searchTerm))
                        Try adjusting your search criteria or browse all labels.
                    @else
                        There are no labels in your Gmail account or there was an issue fetching them.
                    @endif
                </p>
            </div>
        @endif
    </div>
</x-filament::page>