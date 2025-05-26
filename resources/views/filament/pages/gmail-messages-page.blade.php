<x-filament::page>
    <style>
        /* Apple Design System - Dark Mode Optimized */
        :root {
            --system-blue: #007AFF;
            --system-green: #34C759;
            --system-indigo: #5856D6;
            --system-orange: #FF9500;
            --system-pink: #FF2D92;
            --system-purple: #AF52DE;
            --system-red: #FF3B30;
            --system-teal: #5AC8FA;
            --system-yellow: #FFCC00;
        }

        /* Light Mode Variables */
        :root {
            --bg-primary: #FFFFFF;
            --bg-secondary: #F2F2F7;
            --bg-tertiary: #FFFFFF;
            --bg-elevated: #FFFFFF;

            --text-primary: #000000;
            --text-secondary: #3C3C43;
            --text-tertiary: #3C3C4399;
            --text-quaternary: #3C3C4366;

            --fill-primary: #78788033;
            --fill-secondary: #78788028;
            --fill-tertiary: #7676801E;
            --fill-quaternary: #74748014;

            --separator: #3C3C4336;
            --border-color: #D1D1D6;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        }

        /* Dark Mode Variables */
        .dark {
            --bg-primary: #1C1C1E;
            --bg-secondary: #000000;
            --bg-tertiary: #2C2C2E;
            --bg-elevated: #2C2C2E;

            --text-primary: #FFFFFF;
            --text-secondary: #EBEBF599;
            --text-tertiary: #EBEBF54D;
            --text-quaternary: #EBEBF533;

            --fill-primary: #78788066;
            --fill-secondary: #78788052;
            --fill-tertiary: #7676803D;
            --fill-quaternary: #74748029;

            --separator: #54545899;
            --border-color: #38383A;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.6);
            --shadow-md: 0 3px 6px rgba(0,0,0,0.6), 0 3px 6px rgba(0,0,0,0.7);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.8), 0 6px 6px rgba(0,0,0,0.9);
        }

        /* Auto-detect dark mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #1C1C1E;
                --bg-secondary: #000000;
                --bg-tertiary: #2C2C2E;
                --bg-elevated: #2C2C2E;

                --text-primary: #FFFFFF;
                --text-secondary: #EBEBF599;
                --text-tertiary: #EBEBF54D;
                --text-quaternary: #EBEBF533;

                --fill-primary: #78788066;
                --fill-secondary: #78788052;
                --fill-tertiary: #7676803D;
                --fill-quaternary: #74748029;

                --separator: #54545899;
                --border-color: #38383A;

                --shadow-sm: 0 1px 3px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.6);
                --shadow-md: 0 3px 6px rgba(0,0,0,0.6), 0 3px 6px rgba(0,0,0,0.7);
                --shadow-lg: 0 10px 20px rgba(0,0,0,0.8), 0 6px 6px rgba(0,0,0,0.9);
            }
        }

        /* Detect Filament dark mode */
        [class*="dark"] {
            --bg-primary: #1C1C1E;
            --bg-secondary: #000000;
            --bg-tertiary: #2C2C2E;
            --bg-elevated: #2C2C2E;

            --text-primary: #FFFFFF;
            --text-secondary: #EBEBF599;
            --text-tertiary: #EBEBF54D;
            --text-quaternary: #EBEBF533;

            --fill-primary: #78788066;
            --fill-secondary: #78788052;
            --fill-tertiary: #7676803D;
            --fill-quaternary: #74748029;

            --separator: #54545899;
            --border-color: #38383A;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.6);
            --shadow-md: 0 3px 6px rgba(0,0,0,0.6), 0 3px 6px rgba(0,0,0,0.7);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.8), 0 6px 6px rgba(0,0,0,0.9);
        }

        /* Core Layout */
        .apex-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            background: var(--bg-secondary);
            min-height: 100vh;
        }

        /* Grid System */
        .apex-grid {
            display: grid;
            gap: 16px;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 32px;
        }

        @media (min-width: 768px) {
            .metrics-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
            }
        }

        .inbox-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 1024px) {
            .inbox-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Premium Card System */
        .apex-card {
            background: var(--bg-elevated);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .apex-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: var(--shadow-lg);
            border-color: var(--system-blue);
        }

        /* Metric Cards - Enhanced for Dark Mode */
        .metric-card {
            padding: 24px;
            text-align: center;
            background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
            color: white;
            border: none;
            position: relative;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .metric-card.blue { --bg-start: #007AFF; --bg-end: #0051D5; }
        .metric-card.red { --bg-start: #FF3B30; --bg-end: #D70015; }
        .metric-card.green { --bg-start: #34C759; --bg-end: #248A3D; }
        .metric-card.purple { --bg-start: #AF52DE; --bg-end: #8E44AD; }

        .metric-number {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
            margin: 8px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            opacity: 0.95;
        }

        .metric-icon {
            width: 32px;
            height: 32px;
            margin: 0 auto 8px;
            opacity: 0.9;
        }

        /* Filter System - Dark Mode Optimized */
        .filter-system {
            background: var(--bg-elevated);
            border-radius: 16px;
            padding: 4px;
            margin-bottom: 32px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        @media (min-width: 768px) {
            .filter-system {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .filter-button {
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            background: transparent;
            color: var(--text-secondary);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
            position: relative;
        }

        .filter-button.active {
            background: var(--system-blue);
            color: white;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.4);
        }

        .filter-button:hover:not(.active) {
            background: var(--fill-secondary);
            color: var(--text-primary);
            transform: scale(1.05);
        }

        /* Email Cards - Dark Mode Enhanced */
        .email-card {
            padding: 24px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 16px;
            align-items: start;
            position: relative;
        }

        .email-card.client::before {
            content: '';
            position: absolute;
            left: 0;
            top: 16px;
            bottom: 16px;
            width: 4px;
            background: linear-gradient(180deg, var(--system-green), #248A3D);
            border-radius: 2px;
        }

        .email-card.prospect::before {
            content: '';
            position: absolute;
            left: 0;
            top: 16px;
            bottom: 16px;
            width: 4px;
            background: linear-gradient(180deg, var(--system-purple), #8E44AD);
            border-radius: 2px;
        }

        .email-card.unread {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1) 0%, rgba(0, 122, 255, 0.05) 100%);
            border-color: rgba(0, 122, 255, 0.3);
        }

        .email-card.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 16px;
            bottom: 16px;
            width: 4px;
            background: linear-gradient(180deg, var(--system-blue), #0051D5);
            border-radius: 2px;
        }

        /* Avatar System - Dark Mode */
        .avatar-container {
            position: relative;
        }

        .apex-avatar {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            position: relative;
            box-shadow: var(--shadow-md);
        }

        .apex-avatar.client {
            background: linear-gradient(135deg, var(--system-green) 0%, #248A3D 100%);
        }

        .apex-avatar.prospect {
            background: linear-gradient(135deg, var(--system-purple) 0%, #8E44AD 100%);
        }

        .apex-avatar.personal {
            background: linear-gradient(135deg, #8E8E93 0%, #636366 100%);
        }

        .status-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid var(--bg-elevated);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-indicator.client {
            background: var(--system-green);
        }

        .status-indicator.prospect {
            background: var(--system-purple);
        }

        .status-indicator svg {
            width: 10px;
            height: 10px;
            fill: white;
        }

        /* Typography - Dark Mode Optimized */
        .email-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }

        .sender-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 2px 0;
        }

        .sender-email {
            font-size: 0.875rem;
            color: var(--text-tertiary);
            margin: 0 0 8px 0;
        }

        .email-subject {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            line-height: 1.4;
        }

        .email-snippet {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin: 0 0 16px 0;
        }

        .email-time {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            font-weight: 500;
            white-space: nowrap;
        }

        /* Status Badges - Dark Mode */
        .status-badges {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: flex-end;
            margin-top: 4px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-badge.new {
            background: var(--system-blue);
            color: white;
        }

        .status-badge.urgent {
            background: var(--system-red);
            color: white;
        }

        .status-badge.starred {
            background: var(--system-yellow);
            color: black;
        }

        /* Client Panel - Dark Mode */
        .client-panel {
            margin-top: 16px;
            padding: 16px;
            background: var(--fill-primary);
            border-radius: 12px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: center;
            border: 1px solid var(--border-color);
        }

        .client-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .client-badge.client {
            background: var(--system-green);
        }

        .client-badge.prospect {
            background: var(--system-purple);
        }

        .client-info h4 {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 2px 0;
        }

        .client-info p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .client-status-pill {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .client-status-pill.paid {
            background: rgba(52, 199, 89, 0.2);
            color: var(--system-green);
        }

        .client-status-pill.overdue {
            background: rgba(255, 59, 48, 0.2);
            color: var(--system-red);
        }

        /* Action Keypad - Dark Mode */
        .action-keypad {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 16px;
            opacity: 0;
            transform: translateY(12px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .email-card:hover .action-keypad {
            opacity: 1;
            transform: translateY(0);
        }

        .keypad-button {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .keypad-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .keypad-button:hover::before {
            left: 100%;
        }

        .keypad-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .keypad-button.primary {
            background: var(--system-blue);
            color: white;
        }

        .keypad-button.success {
            background: var(--system-green);
            color: white;
        }

        .keypad-button.warning {
            background: var(--system-orange);
            color: white;
        }

        .keypad-button.danger {
            background: var(--system-red);
            color: white;
        }

        /* Empty State - Dark Mode */
        .apex-empty-state {
            text-align: center;
            padding: 48px;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            background: var(--fill-primary);
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 8px 0;
        }

        .empty-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0 0 32px 0;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .apex-container {
                padding: 16px;
            }

            .email-card {
                padding: 16px;
            }

            .keypad-button {
                font-size: 0.75rem;
                padding: 6px 12px;
            }
        }
    </style>

    <div class="apex-container">
        @if (count($messages))
            <!-- Metrics Grid -->
            <div class="apex-grid metrics-grid">
                <div class="apex-card metric-card blue">
                    <div class="metric-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ count($messages) }}</div>
                    <div class="metric-label">Total</div>
                </div>

                <div class="apex-card metric-card red">
                    <div class="metric-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ collect($messages)->where('isRead', false)->count() }}</div>
                    <div class="metric-label">Unread</div>
                </div>

                <div class="apex-card metric-card green">
                    <div class="metric-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ collect($messages)->where('isClient', true)->count() }}</div>
                    <div class="metric-label">Clients</div>
                </div>

                <div class="apex-card metric-card purple">
                    <div class="metric-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ collect($messages)->where('isClient', false)->where('category', '!=', 'personal')->count() }}</div>
                    <div class="metric-label">Prospects</div>
                </div>
            </div>

            <!-- Filter System -->
            <div class="filter-system">
                <button class="filter-button active">All Messages</button>
                <button class="filter-button">Clients</button>
                <button class="filter-button">Prospects</button>
                <button class="filter-button">Unread</button>
            </div>

            <!-- Email Grid -->
            <div class="apex-grid inbox-grid">
                @foreach ($messages as $message)
                    <div class="apex-card email-card
                        {{ ($message['isClient'] ?? false) ? 'client' : (($message['category'] ?? '') != 'personal' ? 'prospect' : '') }}
                        {{ !$message['isRead'] ? 'unread' : '' }}">

                        <!-- Avatar System -->
                        <div class="avatar-container">
                            <div class="apex-avatar {{ ($message['isClient'] ?? false) ? 'client' : (($message['category'] ?? '') != 'personal' ? 'prospect' : 'personal') }}">
                                {{ strtoupper(substr(explode('@', $message['from'])[0], 0, 1)) }}
                                @if ($message['isClient'] ?? false)
                                    <div class="status-indicator client">
                                        <svg viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                    </div>
                                @elseif (($message['category'] ?? '') != 'personal')
                                    <div class="status-indicator prospect">
                                        <svg viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Email Content -->
                        <div class="email-content">
                            <div class="email-header">
                                <div>
                                    <h3 class="sender-name">{{ explode('@', $message['from'])[0] }}</h3>
                                    <p class="sender-email">{{ $message['from'] }}</p>
                                </div>
                                <div style="text-align: right;">
                                    <div class="email-time">{{ \Carbon\Carbon::parse($message['date'])->diffForHumans() }}</div>
                                    <div class="status-badges">
                                        @if (!$message['isRead'])
                                            <span class="status-badge new">New</span>
                                        @endif
                                        @if ($message['isImportant'])
                                            <span class="status-badge urgent">Urgent</span>
                                        @endif
                                        @if ($message['isStarred'])
                                            <span class="status-badge starred">★</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <h4 class="email-subject">{{ $message['subject'] }}</h4>
                            <p class="email-snippet">{{ Str::limit($message['snippet'], 120) }}</p>

                            <!-- Client/Prospect Panel -->
                            @if ($message['isClient'] ?? false)
                                <div class="client-panel">
                                    <div class="client-badge client">
                                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                    </div>
                                    <div class="client-info">
                                        <h4>{{ $message['clientInfo']['name'] }}</h4>
                                        <p>${{ number_format($message['clientInfo']['projectValue']) }} project value</p>
                                    </div>
                                    <div class="client-status-pill {{ $message['clientInfo']['status'] == 'payment_due' ? 'overdue' : 'paid' }}">
                                        {{ $message['clientInfo']['lastInvoice'] }}
                                    </div>
                                </div>
                            @elseif (isset($message['prospectValue']))
                                <div class="client-panel">
                                    <div class="client-badge prospect">
                                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="client-info">
                                        <h4>Hot Prospect</h4>
                                        <p>${{ number_format($message['prospectValue']) }} potential value</p>
                                    </div>
                                    <button class="keypad-button primary">Add to CRM</button>
                                </div>
                            @endif

                            <!-- Action Keypad - 2 per row max -->
                            <div class="action-keypad">
                                <button class="keypad-button primary">Reply</button>
                                @if ($message['isClient'] ?? false)
                                    <button class="keypad-button success">Call Client</button>
                                @else
                                    <button class="keypad-button success">Add Contact</button>
                                @endif
                                <button class="keypad-button warning">Star</button>
                                <button class="keypad-button danger">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Premium Empty State -->
            <div class="apex-empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" fill="var(--text-quaternary)" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="empty-title">No messages</h3>
                <p class="empty-subtitle">Your inbox is empty</p>
                <button wire:click="loadMessages()" class="keypad-button primary" style="opacity: 1; transform: none;">
                    Refresh Inbox
                </button>
            </div>
        @endif
    </div>
</x-filament::page>