<x-filament::page>
    <div class="space-y-4 lg:space-y-6">
        <!-- Mobile-First Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Gmail</h1>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                @if (count($messages))
                    <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 text-xs font-medium px-2 py-1 rounded-full">
                        {{ collect($messages)->where('isRead', false)->count() }} unread
                    </span>
                @endif
                <button wire:click="loadMessages"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Gmail Account Switcher -->
        @if (count($availableGmailAccounts) > 1)
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-600 p-4 lg:p-6 shadow-sm dark:shadow-gray-900/20">
                <label class="block text-sm lg:text-base font-medium text-gray-700 dark:text-gray-200 mb-3 lg:mb-4">Gmail Account</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4">
                    @foreach ($availableGmailAccounts as $account)
                        <button wire:click="switchGmailAccount('{{ $account['gmail_email'] }}')"
                                class="flex items-center gap-2 lg:gap-3 px-4 lg:px-5 py-3 lg:py-4 rounded-lg text-sm lg:text-base font-medium transition-all min-w-0
                                       {{ $selectedGmailAccount === $account['gmail_email']
                                          ? 'bg-blue-100 text-blue-800 border-2 border-blue-300 dark:bg-blue-800 dark:text-blue-100 dark:border-blue-500 shadow-md'
                                          : 'bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 hover:shadow-sm' }}">
                            @if ($account['is_primary'])
                                <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                            <span class="truncate">{{ $account['display_name'] ?: $account['gmail_email'] }}</span>
                        </button>
                    @endforeach
                </div>
                @if ($selectedGmailAccount)
                    <div class="mt-4 lg:mt-5 p-3 lg:p-4 bg-gray-50 dark:bg-gray-800 rounded-md border dark:border-gray-700">
                        <p class="text-sm lg:text-base text-gray-600 dark:text-gray-300">
                            Currently viewing: <span class="font-semibold text-gray-900 dark:text-white">{{ $selectedGmailAccount }}</span>
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Search Bar (prominently placed) -->
        <div class="relative">
            {{ $this->form }}
        </div>

        <!-- Quick Filter Chips -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <button wire:click="showUnreadOnly"
                    class="flex-shrink-0 px-3 py-1 text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                📧 Unread
            </button>
            <button wire:click="showStarredOnly"
                    class="flex-shrink-0 px-3 py-1 text-sm bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors">
                ⭐ Starred
            </button>
            <button wire:click="filterBy('clients')"
                    class="flex-shrink-0 px-3 py-1 text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                👥 Clients
            </button>
            <button wire:click="filterBy('leads')"
                    class="flex-shrink-0 px-3 py-1 text-sm bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors">
                🔥 Leads
            </button>
        </div>

        <!-- Main Content -->
        <div class="lg:grid lg:grid-cols-5 xl:grid-cols-6 lg:gap-8">
            <!-- Desktop Labels Sidebar (hidden on mobile) -->
            <div class="hidden lg:block lg:col-span-1 xl:col-span-1">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-600 p-6 sticky top-6 shadow-sm dark:shadow-gray-900/20">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-6 text-lg">Labels</h3>

                    <!-- Stats (desktop only) -->
                    <div class="grid grid-cols-1 gap-4 mb-8">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($messages) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 dark:bg-red-900/30 rounded-lg border dark:border-red-800">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ collect($messages)->where('isRead', false)->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Unread</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg border dark:border-green-800">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ collect($messages)->where('isClient', true)->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Clients</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg border dark:border-purple-800">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ collect($messages)->where('isClient', false)->where('category', '!=', 'personal')->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Prospects</div>
                        </div>
                    </div>

                    <!-- System Labels -->
                    @if (count($systemLabels) > 0)
                        <div class="space-y-2 mb-8">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">System</h4>
                            @foreach ($systemLabels as $label)
                                <div class="flex items-center justify-between px-4 py-3 text-sm rounded-lg cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-800 {{ in_array($label['id'], $selectedLabels) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border dark:border-blue-800' : 'text-gray-700 dark:text-gray-300' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <span class="truncate">{{ $label['name'] }}</span>
                                    @if ($label['messagesTotal'] > 0)
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2.5 py-1 rounded-full ml-2 flex-shrink-0">{{ $label['messagesTotal'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- User Labels -->
                    @if (count($userLabels) > 0)
                        <div class="space-y-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Custom</h4>
                            @foreach ($userLabels as $label)
                                <div class="flex items-center justify-between px-4 py-3 text-sm rounded-lg cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-800 {{ in_array($label['id'], $selectedLabels) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border dark:border-blue-800' : 'text-gray-700 dark:text-gray-300' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <span class="truncate">{{ $label['name'] }}</span>
                                    @if ($label['messagesTotal'] > 0)
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2.5 py-1 rounded-full ml-2 flex-shrink-0">{{ $label['messagesTotal'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Messages List - PRIORITY CONTENT -->
            <div class="lg:col-span-4 xl:col-span-5">
                @if (count($messages))
                    <!-- Clean Email List Using Components -->
                    <div class="space-y-4 lg:space-y-6">
                        @foreach ($messages as $message)
                            @php
                                // Use EmailClassificationService for clean classification
                                $classifier = app(\App\Services\Gmail\EmailClassificationService::class);
                                $classification = $classifier->classify($message);
                            @endphp

                            <x-gmail.email-card
                                :message="$message"
                                :is-git-hub-notification="$classification['is_github_notification']"
                                :is-git-hub-p-r="$classification['is_github_pr']"
                                :is-git-hub-issue="$classification['is_github_issue']"
                                :is-git-hub-action="$classification['is_github_action']"
                                :is-laravel="$classification['is_laravel']"
                                :is-service-notification="$classification['is_service_notification']"
                                :github-urls="$classification['github_urls']" />
                        @endforeach
                    </div>
                @else
                    <!-- Enhanced Empty State -->
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-600 p-12 lg:p-16 text-center shadow-sm dark:shadow-gray-900/20">
                        <div class="max-w-md mx-auto">
                            <svg class="w-20 h-20 lg:w-24 lg:h-24 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-3">No messages found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-8 text-base lg:text-lg leading-relaxed">
                                Your inbox appears to be empty or no messages match your current filters. Try adjusting your search or refresh to check for new messages.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <button wire:click="loadMessages"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refresh Inbox
                                </button>
                                <button onclick="window.location.reload()"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Rich HTML Email Preview Modal -->
    @if ($showingEmailId && $emailPreview)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end lg:items-center justify-center p-0 lg:p-4" wire:click="closeEmailPreview">
            <!-- Mobile: slide up from bottom, Desktop: centered modal with larger size for rich content -->
            <div class="bg-white dark:bg-gray-800 w-full h-[95vh] lg:h-auto lg:max-h-[95vh] lg:rounded-xl lg:shadow-2xl max-w-6xl xl:max-w-7xl overflow-hidden" wire:click.stop>
                <!-- Enhanced Modal Header -->
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 sticky top-0 z-10">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                {{ $emailPreview['subject'] ?: 'No Subject' }}
                            </h3>
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">From:</span>
                                    <span class="truncate">{{ $emailPreview['from'] }}</span>
                                </div>
                                @if (!empty($emailPreview['to']))
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">To:</span>
                                        <span class="truncate">{{ $emailPreview['to'] }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Date:</span>
                                    <span>{{ $emailPreview['date'] }}</span>
                                </div>
                                @if (!empty($emailPreview['attachments']) && count($emailPreview['attachments']) > 0)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        <span class="font-medium">{{ count($emailPreview['attachments']) }} attachment(s)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <button wire:click="closeEmailPreview"
                                class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Modal Body with Rich HTML Support -->
                <div class="p-6 h-full overflow-y-auto bg-white dark:bg-gray-800">
                    <div class="email-content-container">
                        @if (!empty($emailPreview['body_html']))
                            <!-- Simplified HTML Content (processed by EmailContentService) -->
                            <div class="email-rich-content">
                                {!! $emailPreview['body_html'] !!}
                            </div>
                        @elseif (!empty($emailPreview['body_text']))
                            <!-- Plain text content with improved formatting -->
                            <div class="email-text-content">
                                <pre class="whitespace-pre-wrap font-sans text-gray-900 dark:text-gray-100 leading-relaxed">{{ $emailPreview['body_text'] }}</pre>
                            </div>
                        @else
                            <!-- Fallback to snippet -->
                            <div class="email-snippet-content">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $emailPreview['snippet'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-4 italic">Email content not available. This may be due to privacy settings or email format limitations.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Attachments Section -->
                    @if (!empty($emailPreview['attachments']) && count($emailPreview['attachments']) > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Attachments ({{ count($emailPreview['attachments']) }})
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($emailPreview['attachments'] as $attachment)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $attachment['filename'] ?? 'Unknown file' }}
                                            </p>
                                            @if (!empty($attachment['size']))
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ round($attachment['size'] / 1024, 1) }} KB
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .group:hover .lg\:group-hover\:opacity-100 {
            opacity: 1;
        }

        /* Rich Email Content Styles */
        .email-content-container {
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Email Content Normalization - Reset container but preserve content styling */
        .email-rich-content,
        .email-basic-html {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: #374151 !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            width: auto !important;
            max-width: 100% !important;
            min-width: 0 !important;
        }

        /* Selective reset - preserve text formatting but normalize layout */
        .email-rich-content div,
        .email-rich-content table,
        .email-rich-content tr,
        .email-rich-content td,
        .email-rich-content span,
        .email-basic-html div,
        .email-basic-html table,
        .email-basic-html tr,
        .email-basic-html td,
        .email-basic-html span {
            font-family: inherit !important;
            background: transparent !important;
            border: none !important;
            width: auto !important;
            max-width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content,
            .email-basic-html {
                color: #d1d5db;
            }
        }

        /* Email Typography - Override with controlled spacing */
        .email-rich-content h1,
        .email-rich-content h2,
        .email-rich-content h3,
        .email-rich-content h4,
        .email-rich-content h5,
        .email-rich-content h6,
        .email-basic-html h1,
        .email-basic-html h2,
        .email-basic-html h3,
        .email-basic-html h4,
        .email-basic-html h5,
        .email-basic-html h6 {
            margin: 1.5em 0 0.5em 0 !important;
            font-weight: 600 !important;
            line-height: 1.3 !important;
            font-family: inherit !important;
            color: inherit !important;
        }

        .email-rich-content h1,
        .email-basic-html h1 { font-size: 1.25rem !important; }
        .email-rich-content h2,
        .email-basic-html h2 { font-size: 1.125rem !important; }
        .email-rich-content h3,
        .email-basic-html h3 { font-size: 1rem !important; }
        .email-rich-content h4,
        .email-basic-html h4 { font-size: 0.875rem !important; }
        .email-rich-content h5,
        .email-basic-html h5 { font-size: 0.875rem !important; }
        .email-rich-content h6,
        .email-basic-html h6 { font-size: 0.875rem !important; }

        .email-rich-content p,
        .email-basic-html p {
            margin: 0.75em 0 !important;
            line-height: 1.6 !important;
            font-size: inherit !important;
        }

        /* Allow text formatting elements to work */
        .email-rich-content strong,
        .email-rich-content b,
        .email-basic-html strong,
        .email-basic-html b {
            font-weight: 700 !important;
            color: inherit !important;
        }

        .email-rich-content em,
        .email-rich-content i,
        .email-basic-html em,
        .email-basic-html i {
            font-style: italic !important;
            color: inherit !important;
        }

        .email-rich-content u,
        .email-basic-html u {
            text-decoration: underline !important;
            color: inherit !important;
        }

        /* Allow some controlled spacing */
        .email-rich-content br,
        .email-basic-html br {
            line-height: 1.6 !important;
        }

        /* Email Lists */
        .email-rich-content ul,
        .email-rich-content ol,
        .email-basic-html ul,
        .email-basic-html ol {
            margin: 1em 0 !important;
            padding-left: 1.5em !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .email-rich-content li,
        .email-basic-html li {
            margin: 0.25em 0 !important;
            padding: 0 !important;
        }

        /* Email Links */
        .email-rich-content a,
        .email-basic-html a {
            color: #2563eb !important;
            text-decoration: underline !important;
            word-break: break-all !important;
            font-size: inherit !important;
            font-family: inherit !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content a,
            .email-basic-html a {
                color: #60a5fa !important;
            }
        }

        .email-rich-content a:hover,
        .email-basic-html a:hover {
            text-decoration: none !important;
        }

        /* Email Images */
        .email-rich-content img,
        .email-basic-html img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px !important;
            margin: 1em 0 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            width: auto !important;
        }

        /* Email Tables - Completely override table styling */
        .email-rich-content table,
        .email-basic-html table {
            width: 100% !important;
            max-width: 100% !important;
            border-collapse: collapse !important;
            margin: 1em 0 !important;
            font-size: inherit !important;
            background: transparent !important;
            border: none !important;
        }

        .email-rich-content th,
        .email-rich-content td,
        .email-basic-html th,
        .email-basic-html td {
            padding: 6px 8px !important;
            border: 1px solid #e5e7eb !important;
            text-align: left !important;
            font-size: inherit !important;
            font-family: inherit !important;
            color: inherit !important;
            background: transparent !important;
            width: auto !important;
            max-width: none !important;
            min-width: 0 !important;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content th,
            .email-rich-content td,
            .email-basic-html th,
            .email-basic-html td {
                border-color: #4b5563 !important;
            }
        }

        .email-rich-content th,
        .email-basic-html th {
            background-color: #f9fafb !important;
            font-weight: 600 !important;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content th,
            .email-basic-html th {
                background-color: #374151 !important;
            }
        }

        /* Force override any table layout styling */
        .email-rich-content table[width],
        .email-rich-content table[style],
        .email-basic-html table[width],
        .email-basic-html table[style] {
            width: 100% !important;
            max-width: 100% !important;
        }

        .email-rich-content td[width],
        .email-rich-content td[style],
        .email-basic-html td[width],
        .email-basic-html td[style] {
            width: auto !important;
            max-width: none !important;
        }

        /* Email Code Blocks */
        .email-rich-content code,
        .email-basic-html code {
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875em;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content code,
            .email-basic-html code {
                background-color: #374151;
            }
        }

        .email-rich-content pre,
        .email-basic-html pre {
            background-color: #f3f4f6;
            padding: 1em;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1em 0;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content pre,
            .email-basic-html pre {
                background-color: #374151;
            }
        }

        /* Email Blockquotes */
        .email-rich-content blockquote,
        .email-basic-html blockquote {
            border-left: 4px solid #e5e7eb;
            margin: 1em 0;
            padding: 0.5em 0 0.5em 1em;
            font-style: italic;
            background-color: #f9fafb;
        }

        @media (prefers-color-scheme: dark) {
            .email-rich-content blockquote,
            .email-basic-html blockquote {
                border-left-color: #4b5563;
                background-color: #374151;
            }
        }

        /* Email Responsive Design */
        @media (max-width: 640px) {
            .email-rich-content,
            .email-basic-html {
                font-size: 13px;
            }

            .email-rich-content table,
            .email-basic-html table {
                font-size: 12px;
            }

            .email-rich-content th,
            .email-rich-content td,
            .email-basic-html th,
            .email-basic-html td {
                padding: 6px 8px;
            }
        }

        /* Email Text Content Styling */
        .email-text-content pre {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background: none;
            border: none;
        }

        /* Email Snippet Content */
        .email-snippet-content {
            font-size: 14px;
            line-height: 1.6;
        }

        /* Override problematic email layout styles but preserve text styling */
        .email-rich-content [style*="width"],
        .email-rich-content [style*="background"],
        .email-basic-html [style*="width"],
        .email-basic-html [style*="background"] {
            width: auto !important;
            max-width: 100% !important;
            min-width: 0 !important;
            background: transparent !important;
        }

        /* Override font family but preserve weight and style */
        .email-rich-content [style*="font-family"],
        .email-basic-html [style*="font-family"] {
            font-family: inherit !important;
        }

        /* Override specific marketing email patterns */
        .email-rich-content .container,
        .email-rich-content .wrapper,
        .email-rich-content .content,
        .email-basic-html .container,
        .email-basic-html .wrapper,
        .email-basic-html .content {
            width: auto !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        /* Override center tags and alignment */
        .email-rich-content center,
        .email-basic-html center {
            text-align: left !important;
            width: auto !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Override font tags */
        .email-rich-content font,
        .email-basic-html font {
            font-family: inherit !important;
            font-size: inherit !important;
            color: inherit !important;
        }

        /* Override span styling */
        .email-rich-content span,
        .email-basic-html span {
            font-family: inherit !important;
            color: inherit !important;
            background: transparent !important;
            font-size: inherit !important;
        }

        /* Override any fixed widths */
        .email-rich-content [width="600"],
        .email-rich-content [width="650"],
        .email-rich-content [width="700"],
        .email-basic-html [width="600"],
        .email-basic-html [width="650"],
        .email-basic-html [width="700"] {
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>
</x-filament::page>