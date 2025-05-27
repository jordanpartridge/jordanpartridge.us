<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail Integration - CRM Enhanced with View Filters</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #0f0f17;
            color: #e0e0e0;
            line-height: 1.6;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #ffffff;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            background: #1a1a24;
            border: 1px solid #2a2a3a;
            border-radius: 8px;
            padding: 10px 40px 10px 16px;
            color: #e0e0e0;
            width: 300px;
            transition: all 0.2s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            color: #6b7280;
        }

        /* AI Insights Bar */
        .ai-insights-bar {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border: 1px solid #4c1d95;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .ai-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ai-insights-content {
            flex: 1;
        }

        .ai-insights-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .ai-insights-text {
            font-size: 14px;
            color: #c7d2fe;
        }

        .ai-action-btn {
            background: #6366f1;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .ai-action-btn:hover {
            background: #5558e3;
            transform: translateY(-1px);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: #1a1a24;
            border: 1px solid #2a2a3a;
            color: #e0e0e0;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .mobile-menu-toggle:hover {
            background: #2a2a3a;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Main Layout */
        .main-content {
            display: grid;
            grid-template-columns: 280px 1fr 380px;
            gap: 24px;
            margin-top: 24px;
        }

        /* Sidebar with Smart Filters */
        .sidebar {
            background: #1a1a24;
            border: 1px solid #2a2a3a;
            border-radius: 12px;
            padding: 20px;
            height: fit-content;
            transition: transform 0.3s ease;
        }

        .sidebar h3 {
            font-size: 16px;
            margin-bottom: 16px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .smart-filters {
            margin-bottom: 24px;
        }

        .filter-item {
            padding: 10px 12px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
            transition: all 0.2s;
        }

        .filter-item:hover {
            background: #2a2a3a;
        }

        .filter-item.active {
            background: #6366f1;
            color: white;
        }

        .filter-icon {
            margin-right: 8px;
        }

        .filter-count {
            font-size: 12px;
            background: #374151;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .filter-item.active .filter-count {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Email List with CRM Features */
        .email-list {
            background: #1a1a24;
            border: 1px solid #2a2a3a;
            border-radius: 12px;
            overflow: hidden;
        }

        .email-list-header {
            padding: 20px;
            border-bottom: 1px solid #2a2a3a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-toggle {
            display: flex;
            gap: 4px;
            background: #252530;
            padding: 4px;
            border-radius: 6px;
        }

        .view-btn {
            padding: 6px 12px;
            border: none;
            background: transparent;
            color: #9ca3af;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .view-btn.active {
            background: #6366f1;
            color: white;
        }

        /* View-specific styles */
        .view-content {
            display: none;
        }

        .view-content.active {
            display: block;
        }

        /* Email Item with CRM Intelligence */
        .email-item {
            padding: 16px 20px;
            border-bottom: 1px solid #2a2a3a;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .email-item:hover {
            background: #252530;
        }

        .email-item-content {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .sender-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
        }

        .sender-avatar.prospect {
            background: #10b981;
        }

        .sender-avatar.lead {
            background: #f59e0b;
        }

        .email-details {
            flex: 1;
        }

        .email-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .email-from {
            font-weight: 600;
            color: #ffffff;
        }

        .client-badge {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .client-badge.existing {
            background: #065f46;
            color: #6ee7b7;
        }

        .client-badge.prospect {
            background: #7c2d12;
            color: #fed7aa;
        }

        .client-badge.lead {
            background: #78350f;
            color: #fde68a;
        }

        .email-time {
            font-size: 12px;
            color: #9ca3af;
        }

        .email-subject {
            font-weight: 500;
            margin-bottom: 4px;
            color: #e0e0e0;
        }

        .email-preview {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        /* CRM Actions */
        .crm-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #2a2a3a;
        }

        .crm-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            border: 1px solid #374151;
            background: transparent;
            color: #d1d5db;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .crm-btn:hover {
            background: #374151;
            color: #ffffff;
        }

        .crm-btn.primary {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .crm-btn.primary:hover {
            background: #5558e3;
        }

        .star-btn {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .star-btn:hover {
            color: #fbbf24;
            background: rgba(251, 191, 36, 0.1);
        }

        .star-btn.starred {
            color: #fbbf24;
        }

        /* Email Action Buttons */
        .email-action-btn {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .email-action-btn:hover {
            color: #e5e7eb;
            background: rgba(156, 163, 175, 0.1);
        }

        .email-action-btn.delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Quick Search Buttons */
        .quick-search-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .quick-search-btn {
            background: #2a2a3a;
            border: 1px solid #3a3a4a;
            color: #9ca3af;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .quick-search-btn:hover {
            background: #3a3a4a;
            color: #e5e7eb;
            border-color: #4a4a5a;
        }

        .quick-search-btn.clear {
            background: #374151;
            border-color: #4b5563;
        }

        .quick-search-btn.clear:hover {
            background: #ef4444;
            border-color: #dc2626;
            color: #ffffff;
        }

        /* Business Intelligence Panel */
        .intel-panel {
            background: #1a1a24;
            border: 1px solid #2a2a3a;
            border-radius: 12px;
            padding: 20px;
            height: fit-content;
        }

        /* Quick Stats for different views */
        .view-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #252530;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
        }

        .stat-label {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .main-content {
                grid-template-columns: 280px 1fr;
            }

            .intel-panel {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .mobile-menu-toggle {
                display: flex;
            }

            .main-content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                z-index: 999;
                transform: translateX(-100%);
                overflow-y: auto;
                border-radius: 0;
                border-right: 1px solid #2a2a3a;
                border-left: none;
                border-top: none;
                border-bottom: none;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .search-bar input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .header-actions {
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .search-bar input {
                width: 100%;
                min-width: 200px;
            }

            .quick-search-buttons {
                flex-wrap: wrap;
            }

            .ai-insights-bar {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .ai-insights-content {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" onclick="toggleMobileSidebar()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    Filters
                </button>
                <h1>Gmail Integration</h1>
            </div>
            <div class="header-actions">
                <div class="search-bar">
                    <input type="text" placeholder="Search emails, contacts, or companies..." wire:model.live="searchTerm">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>

                <!-- Quick Search Buttons -->
                <div class="quick-search-buttons">
                    <button class="quick-search-btn" wire:click="quickSearch('unread')" title="Unread emails">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        Unread
                    </button>
                    <button class="quick-search-btn" wire:click="quickSearch('today')" title="Today's emails">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Today
                    </button>
                    <button class="quick-search-btn" wire:click="quickSearch('attachments')" title="Emails with attachments">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Files
                    </button>
                    @if ($searchTerm)
                        <button class="quick-search-btn clear" wire:click="clearSearch()" title="Clear search">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Clear
                        </button>
                    @endif
                </div>
                <button class="ai-action-btn" wire:click="syncClientsFromEmails">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    Smart Sync
                </button>
            </div>
        </div>

        <!-- AI Insights Bar -->
        <div class="ai-insights-bar">
            <div class="ai-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                </svg>
            </div>
            <div class="ai-insights-content">
                <div class="ai-insights-title">AI Business Intelligence Active</div>
                <div class="ai-insights-text">
                    Found {{ count(collect($messages)->where('category', 'prospect_inquiry')) }} potential leads and
                    {{ count(collect($messages)->where('category', 'client_communication')) }} client opportunities in your recent emails.
                    {{ count(collect($messages)->where('isClient', false)) }} contacts ready for CRM import.
                </div>
            </div>
            <button class="ai-action-btn" wire:click="loadMessages">Review All</button>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" onclick="closeMobileSidebar()"></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Smart Sidebar -->
            <aside class="sidebar" id="mobile-sidebar">
                <div class="smart-filters">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        </svg>
                        Smart Filters
                    </h3>
                    <div class="filter-item {{ $filter === 'opportunities' ? 'active' : '' }}" wire:click="filterBy('opportunities')">
                        <span><span class="filter-icon">🎯</span>Business Opportunities</span>
                        <span class="filter-count">{{ count(collect($messages)->where('category', 'prospect_inquiry')) }}</span>
                    </div>
                    <div class="filter-item {{ $filter === 'clients' ? 'active' : '' }}" wire:click="filterBy('clients')">
                        <span><span class="filter-icon">🤝</span>Client Emails</span>
                        <span class="filter-count">{{ count(collect($messages)->where('isClient', true)) }}</span>
                    </div>
                    <div class="filter-item {{ $filter === 'leads' ? 'active' : '' }}" wire:click="filterBy('leads')">
                        <span><span class="filter-icon">🔥</span>Hot Leads</span>
                        <span class="filter-count">{{ count(collect($messages)->where('urgency', 'high')) }}</span>
                    </div>
                    <div class="filter-item {{ $filter === 'followup' ? 'active' : '' }}" wire:click="filterBy('followup')">
                        <span><span class="filter-icon">📅</span>Follow-ups Due</span>
                        <span class="filter-count">{{ count(collect($messages)->where('category', 'payment_inquiry')) }}</span>
                    </div>
                    <div class="filter-item {{ $filter === 'starred' ? 'active' : '' }}" wire:click="showStarredOnly">
                        <span><span class="filter-icon">⭐</span>Starred</span>
                        <span class="filter-count">{{ count(collect($messages)->where('isStarred', true)) }}</span>
                    </div>
                </div>

                <h3 style="margin-top: 24px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="9" x2="15" y2="9"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    Standard Folders
                </h3>
                @if (count($systemLabels) > 0)
                    @foreach ($systemLabels as $label)
                        <div class="filter-item {{ in_array($label['id'], $selectedLabels) ? 'active' : '' }}"
                             wire:click="toggleLabel('{{ $label['id'] }}')">
                            <span>{{ $label['name'] }}</span>
                            <span class="filter-count">{{ $label['messagesTotal'] ?? 0 }}</span>
                        </div>
                    @endforeach
                @endif
            </aside>

            <!-- Email List with CRM Intelligence -->
            <div class="email-list">
                <div class="email-list-header">
                    <div class="view-toggle">
                        <button class="view-btn active">All Emails</button>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <span style="font-size: 14px; color: #9ca3af;">
                            Showing {{ count($messages) }} emails with CRM intelligence
                            @if (!empty($selectedLabels) && $selectedLabels !== ['INBOX'])
                                - Filter: {{ implode(', ', $selectedLabels) }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Email Messages -->
                <div class="view-content active">
                    @if (count($messages) > 0)
                        @foreach ($messages as $message)
                            <div class="email-item">
                                <div class="email-item-content">
                                    <div class="sender-avatar {{ $message['isClient'] ? 'existing' : ($message['category'] === 'prospect_inquiry' ? 'prospect' : ($message['urgency'] === 'high' ? 'lead' : '')) }}">
                                        {{ strtoupper(substr($message['from_name'] ?: $message['from'], 0, 2)) }}
                                    </div>
                                    <div class="email-details">
                                        <div class="email-header">
                                            <div class="sender-info">
                                                <span class="email-from">{{ $message['from_name'] ?: explode('@', $message['from'])[0] ?? 'Unknown' }}</span>
                                                @if ($message['isClient'])
                                                    <span class="client-badge existing">Client</span>
                                                @elseif ($message['category'] === 'prospect_inquiry')
                                                    <span class="client-badge prospect">New Lead</span>
                                                @elseif ($message['urgency'] === 'high')
                                                    <span class="client-badge lead">Hot Lead</span>
                                                @endif
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span class="email-time">{{ \Carbon\Carbon::parse($message['date'])->diffForHumans() }}</span>

                                                <!-- Email Action Buttons -->
                                                <div style="display: flex; align-items: center; gap: 4px;">
                                                    <!-- Mark as Read/Unread -->
                                                    @if (isset($message['isUnread']) && $message['isUnread'])
                                                        <button class="email-action-btn" title="Mark as read"
                                                                wire:click="markAsRead('{{ $message['id'] }}')">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button class="email-action-btn" title="Mark as unread"
                                                                wire:click="markAsUnread('{{ $message['id'] }}')">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                                                <line x1="1" y1="1" x2="23" y2="23"></line>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    <!-- Archive -->
                                                    <button class="email-action-btn" title="Archive"
                                                            wire:click="archiveEmail('{{ $message['id'] }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="21,8 21,21 3,21 3,8"></polyline>
                                                            <rect x="1" y="3" width="22" height="5"></rect>
                                                            <line x1="10" y1="12" x2="14" y2="12"></line>
                                                        </svg>
                                                    </button>

                                                    <!-- Star -->
                                                    <button class="star-btn {{ $message['isStarred'] ? 'starred' : '' }}"
                                                            wire:click="toggleStar('{{ $message['id'] }}')">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $message['isStarred'] ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"></polygon>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete -->
                                                    <button class="email-action-btn delete" title="Delete"
                                                            wire:click="deleteEmail('{{ $message['id'] }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3,6 5,6 21,6"></polyline>
                                                            <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="email-subject">{{ $message['subject'] }}</div>
                                        <div class="email-preview">{{ Str::limit($message['snippet'], 120) }}</div>

                                        @if ($message['category'] === 'prospect_inquiry' || !$message['isClient'])
                                            <div class="crm-actions">
                                                <button class="crm-btn primary" wire:click="createContactFromEmail('{{ $message['id'] }}')">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="8.5" cy="7" r="4"></circle>
                                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                                    </svg>
                                                    Create Contact
                                                </button>
                                                <button class="crm-btn" wire:click="showEmailPreview('{{ $message['id'] }}')">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                    </svg>
                                                    View Details
                                                </button>
                                            </div>
                                        @else
                                            <div class="crm-actions">
                                                <button class="crm-btn" wire:click="showEmailPreview('{{ $message['id'] }}')">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    View Email
                                                </button>
                                                <button class="crm-btn">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                    </svg>
                                                    Log Activity
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 40px; text-align: center; color: #9ca3af;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 16px;">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <h3 style="margin-bottom: 8px;">No emails found</h3>
                            <p>Try adjusting your filters or check your Gmail connection.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dynamic Intelligence Panel -->
            <aside class="intel-panel">
                <h3 style="font-size: 18px; margin-bottom: 16px;">Email Intelligence</h3>
                <div class="view-stats">
                    <div class="stat-card">
                        <div class="stat-value">{{ count($messages) }}</div>
                        <div class="stat-label">Total Emails</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ count(collect($messages)->where('category', 'prospect_inquiry')) }}</div>
                        <div class="stat-label">New Leads</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ count(collect($messages)->where('isClient', true)) }}</div>
                        <div class="stat-label">Client Emails</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ count(collect($messages)->where('urgency', 'high')) }}</div>
                        <div class="stat-label">Action Required</div>
                    </div>
                </div>

                <h4 style="font-size: 14px; color: #9ca3af; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.05em;">Quick Actions</h4>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <button class="crm-btn" style="width: 100%; justify-content: center;" wire:click="syncClientsFromEmails">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Import All New Contacts
                    </button>
                    <button class="crm-btn" style="width: 100%; justify-content: center;" wire:click="loadMessages">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                        Refresh Intelligence
                    </button>
                </div>

                @if (!empty($selectedLabels) && count($selectedLabels) > 1)
                    <h4 style="font-size: 14px; color: #9ca3af; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.05em;">Active Filters</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach ($selectedLabels as $labelId)
                            @php
                                $labelInfo = collect($availableLabels)->firstWhere('id', $labelId);
                            @endphp
                            <span style="background: #374151; color: #d1d5db; padding: 4px 8px; border-radius: 12px; font-size: 12px; display: flex; align-items: center; gap: 4px;">
                                {{ $labelInfo['name'] ?? $labelId }}
                                <button wire:click="toggleLabel('{{ $labelId }}')" style="background: none; border: none; color: inherit; cursor: pointer; padding: 0; margin-left: 4px;">×</button>
                            </span>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </div>

    <!-- Email Preview Modal -->
    @if ($showingEmailId && $emailPreview)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); display: flex; align-items: center; justify-content: center; z-index: 1000;" wire:click="closeEmailPreview">
            <div style="background: #1a1a24; border: 1px solid #2a2a3a; border-radius: 12px; max-width: 800px; width: 90%; max-height: 80vh; overflow: hidden;" wire:click.stop>
                <div style="padding: 20px; border-bottom: 1px solid #2a2a3a; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #ffffff;">{{ $emailPreview['subject'] }}</h3>
                    <button wire:click="closeEmailPreview" style="background: none; border: none; color: #9ca3af; cursor: pointer; padding: 8px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div style="padding: 20px; max-height: 60vh; overflow-y: auto;">
                    <div style="margin-bottom: 16px;">
                        <div style="color: #9ca3af; font-size: 14px; margin-bottom: 8px;">From: {{ $emailPreview['from'] }}</div>
                        <div style="color: #9ca3af; font-size: 14px;">Date: {{ $emailPreview['date'] }}</div>
                    </div>
                    <div style="color: #e0e0e0; line-height: 1.6;">
                        {!! $emailPreview['body_html'] ?: nl2br(e($emailPreview['body_text'] ?: $emailPreview['snippet'])) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }

        // Close sidebar when clicking on filter items (for better UX)
        document.addEventListener('DOMContentLoaded', function() {
            const filterItems = document.querySelectorAll('.filter-item');
            filterItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Only close on mobile
                    if (window.innerWidth <= 1024) {
                        setTimeout(closeMobileSidebar, 100); // Small delay to allow Livewire action to process
                    }
                });
            });
        });

        // Close sidebar on window resize if screen gets larger
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeMobileSidebar();
            }
        });
    </script>
</body>
</html>