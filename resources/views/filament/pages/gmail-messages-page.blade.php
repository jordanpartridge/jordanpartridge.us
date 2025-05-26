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

        /* Gmail Layout with Sidebar */
        .gmail-layout {
            display: flex;
            gap: 0;
            min-height: calc(100vh - 48px);
            background: var(--bg-secondary);
        }

        /* Labels Sidebar */
        .labels-sidebar {
            width: 280px;
            background: var(--bg-primary);
            border-right: 1px solid var(--border-color);
            padding: 20px;
            overflow-y: auto;
            flex-shrink: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .labels-sidebar.hidden {
            width: 0;
            padding: 0;
            opacity: 0;
            overflow: hidden;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .clear-filters-btn {
            background: var(--system-red);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .clear-filters-btn:hover {
            background: #D70015;
            transform: scale(1.05);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .quick-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-action-btn:hover {
            background: var(--bg-primary);
            border-color: var(--system-blue);
            color: var(--text-primary);
        }

        .quick-action-btn.starred:hover {
            border-color: var(--system-yellow);
            color: var(--system-yellow);
        }

        .quick-action-btn.important:hover {
            border-color: var(--system-orange);
            color: var(--system-orange);
        }

        /* Label Sections */
        .label-section {
            margin-bottom: 24px;
        }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 8px 0;
        }

        .label-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .label-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .label-item:hover {
            background: var(--fill-secondary);
            border-color: var(--system-blue);
        }

        .label-item.active {
            background: var(--system-blue);
            color: white;
            border-color: var(--system-blue);
        }

        .label-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .label-indicator.blue { background: var(--system-blue); }
        .label-indicator.green { background: var(--system-green); }
        .label-indicator.yellow { background: var(--system-yellow); }
        .label-indicator.red { background: var(--system-red); }
        .label-indicator.gray { background: #8E8E93; }
        .label-indicator.orange { background: var(--system-orange); }
        .label-indicator.purple { background: var(--system-purple); }

        .label-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .label-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .label-item.active .label-name {
            color: white;
        }

        .unread-count {
            background: var(--system-red);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 8px;
        }

        .label-item.active .unread-count {
            background: rgba(255,255,255,0.3);
        }

        .total-count {
            font-size: 11px;
            color: var(--text-tertiary);
            margin-left: 8px;
        }

        .label-item.active .total-count {
            color: rgba(255,255,255,0.7);
        }

        /* Selected Labels */
        .selected-labels {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .selected-label-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .selected-tag {
            display: flex;
            align-items: center;
            background: var(--system-blue);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .remove-tag {
            background: none;
            border: none;
            color: white;
            margin-left: 4px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }

        /* Messages Area */
        .messages-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-secondary);
            overflow: hidden;
        }

        .messages-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .labels-toggle-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .labels-toggle-btn:hover {
            background: var(--bg-primary);
            border-color: var(--system-blue);
            color: var(--text-primary);
        }

        .current-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .active-filters-info {
            font-size: 14px;
            color: var(--text-secondary);
            background: var(--fill-secondary);
            padding: 6px 12px;
            border-radius: 6px;
        }

        .topbar-search {
            max-width: 400px;
            min-width: 300px;
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
            cursor: pointer;
        }

        .email-card.expanded {
            grid-template-rows: auto auto;
        }

        .email-card.expanded .email-content {
            grid-column: 1 / -1;
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

        /* Hover Preview - Dark Mode */
        .hover-preview {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-elevated);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            box-shadow: var(--shadow-lg);
            z-index: 10;
            max-height: 200px;
            overflow-y: auto;
            margin-top: 8px;
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            pointer-events: none;
        }

        .email-card:hover .hover-preview {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .hover-preview-content {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .hover-preview-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--separator);
        }

        .hover-preview-date {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            font-weight: 500;
        }

        /* Expanded Content */
        .expanded-content {
            margin-top: 16px;
            padding: 16px;
            background: var(--fill-primary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: none;
        }

        .email-card.expanded .expanded-content {
            display: block;
        }

        .expanded-body {
            font-size: 0.875rem;
            color: var(--text-primary);
            line-height: 1.6;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        /* Email Content Styling - Support for HTML emails */
        .expanded-body.prose,
        .hover-preview-content.prose,
        .prose {
            --tw-prose-body: var(--text-primary);
            --tw-prose-headings: var(--text-primary);
            --tw-prose-lead: var(--text-secondary);
            --tw-prose-links: var(--system-blue);
            --tw-prose-bold: var(--text-primary);
            --tw-prose-counters: var(--text-secondary);
            --tw-prose-bullets: var(--text-tertiary);
            --tw-prose-hr: var(--border-color);
            --tw-prose-quotes: var(--text-primary);
            --tw-prose-quote-borders: var(--border-color);
            --tw-prose-captions: var(--text-secondary);
            --tw-prose-code: var(--system-purple);
            --tw-prose-pre-code: var(--text-primary);
            --tw-prose-pre-bg: var(--bg-tertiary);
            --tw-prose-th-borders: var(--border-color);
            --tw-prose-td-borders: var(--border-color);
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .prose blockquote {
            border-left: 4px solid var(--system-blue);
            padding-left: 16px;
            margin: 16px 0;
            font-style: italic;
        }

        .prose pre {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            overflow-x: auto;
        }

        .prose table {
            border-collapse: collapse;
            width: 100%;
            margin: 16px 0;
        }

        .prose table th,
        .prose table td {
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            text-align: left;
        }

        .prose table th {
            background: var(--fill-primary);
            font-weight: 600;
        }

        /* Full Screen Email Modal Enhancements */
        .email-modal-content {
            font-size: 16px;
            line-height: 1.7;
            max-width: none;
        }

        .email-modal-content h1,
        .email-modal-content h2,
        .email-modal-content h3 {
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }

        .email-modal-content p {
            margin-bottom: 1em;
        }

        .email-modal-content a {
            color: var(--system-blue);
            text-decoration: underline;
        }

        .email-modal-content a:hover {
            color: var(--system-purple);
        }

        /* Scrollbar styling for email content */
        .email-content-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .email-content-scroll::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 4px;
        }

        .email-content-scroll::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .email-content-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--text-tertiary);
        }

        .expand-toggle {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            color: var(--system-blue);
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: color 0.2s;
        }

        .expand-toggle:hover {
            color: var(--system-purple);
        }

        .expand-toggle svg {
            width: 12px;
            height: 12px;
            transition: transform 0.2s;
        }

        .email-card.expanded .expand-toggle svg {
            transform: rotate(180deg);
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

            .hover-preview {
                position: relative;
                top: auto;
                left: auto;
                right: auto;
                margin-top: 16px;
                transform: none;
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>

    <div class="apex-container">
        <!-- Main Layout: Labels Sidebar + Messages -->
        <div class="gmail-layout">
            <!-- Labels Sidebar -->
            <div class="labels-sidebar {{ $showLabelsPanel ? 'visible' : 'hidden' }}">
                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <h3 class="sidebar-title">Labels</h3>
                    <button wire:click="clearLabelFilters" class="clear-filters-btn">
                        Clear All
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button wire:click="selectOnlyLabel('STARRED')" class="quick-action-btn starred">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Starred
                    </button>
                    <button wire:click="selectOnlyLabel('IMPORTANT')" class="quick-action-btn important">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Important
                    </button>
                </div>

                <!-- System Labels -->
                @if (count($systemLabels) > 0)
                    <div class="label-section">
                        <h4 class="section-label">System</h4>
                        <div class="label-list">
                            @foreach ($systemLabels as $label)
                                <div class="label-item {{ in_array($label['id'], $selectedLabels) ? 'active' : '' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <div class="label-indicator {{ $label['color'] }}"></div>
                                    <div class="label-content">
                                        <div class="label-name">{{ $label['name'] }}</div>
                                        @if ($label['messagesUnread'] > 0)
                                            <div class="unread-count">{{ $label['messagesUnread'] }}</div>
                                        @endif
                                    </div>
                                    @if ($label['messagesTotal'] > 0)
                                        <div class="total-count">{{ number_format($label['messagesTotal']) }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- User Labels -->
                @if (count($userLabels) > 0)
                    <div class="label-section">
                        <h4 class="section-label">Custom</h4>
                        <div class="label-list">
                            @foreach ($userLabels as $label)
                                <div class="label-item {{ in_array($label['id'], $selectedLabels) ? 'active' : '' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <div class="label-indicator {{ $label['color'] }}"></div>
                                    <div class="label-content">
                                        <div class="label-name">{{ $label['name'] }}</div>
                                        @if ($label['messagesUnread'] > 0)
                                            <div class="unread-count">{{ $label['messagesUnread'] }}</div>
                                        @endif
                                    </div>
                                    @if ($label['messagesTotal'] > 0)
                                        <div class="total-count">{{ number_format($label['messagesTotal']) }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Selected Labels Display -->
                @if (count($selectedLabels) > 0)
                    <div class="selected-labels">
                        <h4 class="section-label">Active Filters</h4>
                        <div class="selected-label-tags">
                            @foreach ($selectedLabels as $labelId)
                                @php
                                    $labelData = collect($availableLabels)->firstWhere('id', $labelId);
                                @endphp
                                @if ($labelData)
                                    <div class="selected-tag">
                                        <span>{{ $labelData['name'] }}</span>
                                        <button wire:click="toggleLabel('{{ $labelId }}')" class="remove-tag">×</button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Messages Area -->
            <div class="messages-area">
                <!-- Top Bar with Search and Toggle -->
                <div class="messages-topbar">
                    <div class="topbar-left">
                        <button wire:click="toggleLabelsPanel" class="labels-toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            Labels
                        </button>

                        @if (count($selectedLabels) > 1)
                            <div class="active-filters-info">
                                Filtering by {{ count($selectedLabels) }} labels
                            </div>
                        @else
                            @php
                                $currentLabel = collect($availableLabels)->firstWhere('id', $selectedLabels[0] ?? 'INBOX');
                            @endphp
                            <div class="current-label">
                                <div class="label-indicator {{ $currentLabel['color'] ?? 'blue' }}"></div>
                                {{ $currentLabel['name'] ?? 'Inbox' }}
                            </div>
                        @endif
                    </div>

                    <!-- Search Form -->
                    <div class="topbar-search">
                        {{ $this->form }}
                    </div>
                </div>

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
                        {{ !$message['isRead'] ? 'unread' : '' }}
                        {{ $this->isExpanded($message['id']) ? 'expanded' : '' }}"
                        wire:mouseenter="showHoverPreview('{{ $message['id'] }}')"
                        wire:mouseleave="hideHoverPreview">

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

                            <!-- Expand Toggle -->
                            <div class="expand-toggle" wire:click="toggleExpanded('{{ $message['id'] }}')">
                                {{ $this->isExpanded($message['id']) ? 'Show Less' : 'Show More' }}
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <!-- Expanded Content -->
                            @if ($this->isExpanded($message['id']))
                                <div class="expanded-content">
                                    <div class="expanded-body prose dark:prose-invert max-w-none">
                                        @if (!empty($message['body_html'] ?? ''))
                                            {!! $message['body_html'] !!}
                                        @elseif (!empty($message['body_text'] ?? ''))
                                            {!! nl2br(e($message['body_text'])) !!}
                                        @else
                                            {!! nl2br(e($message['snippet'])) !!}
                                        @endif
                                    </div>
                                </div>
                            @endif

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
                                <button class="keypad-button primary" wire:click="showEmailPreview('{{ $message['id'] }}')">
                                    View Email
                                </button>
                                @if ($message['isClient'] ?? false)
                                    <button class="keypad-button success">Call Client</button>
                                @else
                                    <button class="keypad-button success">Add Contact</button>
                                @endif
                                <button class="keypad-button warning" wire:click="toggleStar('{{ $message['id'] }}')">
                                    {{ $message['isStarred'] ? 'Unstar' : 'Star' }}
                                </button>
                                <button class="keypad-button danger" wire:click="deleteEmail('{{ $message['id'] }}')">
                                    Delete
                                </button>
                            </div>

                            <!-- Hover Preview -->
                            @if ($hoveredEmailId === $message['id'] && $hoverPreview)
                                <div class="hover-preview">
                                    <div class="hover-preview-meta">
                                        <strong>{{ $hoverPreview['subject'] }}</strong>
                                        <span class="hover-preview-date">{{ $hoverPreview['date'] }}</span>
                                    </div>
                                    <div class="hover-preview-content prose dark:prose-invert max-w-none">
                                        @if (!empty($hoverPreview['body_html'] ?? ''))
                                            {!! Str::limit(strip_tags($hoverPreview['body_html']), 200) !!}
                                        @elseif (!empty($hoverPreview['body_text'] ?? ''))
                                            {!! nl2br(e(Str::limit($hoverPreview['body_text'], 200))) !!}
                                        @else
                                            {!! nl2br(e(Str::limit($hoverPreview['snippet'], 200))) !!}
                                        @endif
                                    </div>
                                </div>
                            @endif
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
            </div> <!-- End messages-area -->
        </div> <!-- End gmail-layout -->
    </div> <!-- End apex-container -->

    <!-- Email Preview Modal - Full Screen -->
    @if ($showingEmailId && $emailPreview)
        <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex"
             wire:click="closeEmailPreview"
             x-data="{
                 init() {
                     this.$nextTick(() => this.$refs.modal.focus())
                 }
             }"
             @keydown.escape.window="$wire.closeEmailPreview()">
            <div class="w-full h-full bg-white dark:bg-gray-900 flex flex-col"
                 wire:click.stop
                 x-ref="modal"
                 tabindex="-1"
                 style="outline: none;">
                <!-- Modal Header - Fixed -->
                <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-900">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white truncate">
                                {{ $emailPreview['subject'] }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                <span class="font-medium">From:</span> {{ $emailPreview['from'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $emailPreview['date'] }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 ml-4">
                            <!-- Star Toggle -->
                            <button wire:click="toggleStar('{{ $emailPreview['id'] }}')"
                                    class="p-2 rounded-lg transition-colors {{ $emailPreview['isStarred'] ? 'text-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'text-gray-400 hover:text-yellow-500 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                <svg class="w-5 h-5" fill="{{ $emailPreview['isStarred'] ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </button>
                            <!-- Close Button -->
                            <button wire:click="closeEmailPreview"
                                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Labels -->
                    @if (!empty($emailPreview['labels']))
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach ($emailPreview['labels'] as $label)
                                @if (!in_array($label, ['INBOX', 'UNREAD', 'IMPORTANT']))
                                    <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full">
                                        {{ $label }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Modal Body - Scrollable Email Content -->
                <div class="flex-1 overflow-hidden">
                    <div class="h-full overflow-y-auto px-6 py-6 email-content-scroll">
                        <div class="prose dark:prose-invert max-w-none prose-lg email-modal-content">
                            @if (!empty($emailPreview['body_html'] ?? ''))
                                {!! $emailPreview['body_html'] !!}
                            @elseif (!empty($emailPreview['body_text'] ?? ''))
                                {!! nl2br(e($emailPreview['body_text'])) !!}
                            @elseif (!empty($emailPreview['snippet'] ?? ''))
                                <div class="text-gray-600 dark:text-gray-300">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Preview from snippet:</p>
                                    {!! nl2br(e($emailPreview['snippet'])) !!}
                                </div>
                            @else
                                <div class="text-gray-500 dark:text-gray-400 italic text-center py-8">
                                    <p>No content available</p>
                                    @if (app()->environment('local'))
                                        <div class="text-xs mt-4 text-left">
                                            Debug info:<br>
                                            Body HTML: {{ !empty($emailPreview['body_html']) ? 'Present (' . strlen($emailPreview['body_html']) . ' chars)' : 'Empty' }}<br>
                                            Body Text: {{ !empty($emailPreview['body_text']) ? 'Present (' . strlen($emailPreview['body_text']) . ' chars)' : 'Empty' }}<br>
                                            Snippet: {{ !empty($emailPreview['snippet']) ? 'Present (' . strlen($emailPreview['snippet']) . ' chars)' : 'Empty' }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer - Fixed -->
                <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ count($emailPreview['labels']) }} labels
                            </span>
                            @if ($emailPreview['isRead'])
                                <span class="text-xs text-green-600 dark:text-green-400">• Read</span>
                            @else
                                <span class="text-xs text-blue-600 dark:text-blue-400">• Unread</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="closeEmailPreview"
                                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament::page>