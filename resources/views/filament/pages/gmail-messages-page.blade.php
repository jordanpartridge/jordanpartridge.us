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
        <div class="lg:grid lg:grid-cols-4 lg:gap-6">
            <!-- Desktop Labels Sidebar (hidden on mobile) -->
            <div class="hidden lg:block lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sticky top-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Labels</h3>

                    <!-- Stats (desktop only) -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ count($messages) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-red-600">{{ collect($messages)->where('isRead', false)->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Unread</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ collect($messages)->where('isClient', true)->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Clients</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-purple-600">{{ collect($messages)->where('isClient', false)->where('category', '!=', 'personal')->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Prospects</div>
                        </div>
                    </div>

                    <!-- System Labels -->
                    @if (count($systemLabels) > 0)
                        <div class="space-y-1">
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">System</h4>
                            @foreach ($systemLabels as $label)
                                <div class="flex items-center justify-between px-3 py-2 text-sm rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ in_array($label['id'], $selectedLabels) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <span>{{ $label['name'] }}</span>
                                    @if ($label['messagesTotal'] > 0)
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">{{ $label['messagesTotal'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- User Labels -->
                    @if (count($userLabels) > 0)
                        <div class="space-y-1 mt-6">
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Custom</h4>
                            @foreach ($userLabels as $label)
                                <div class="flex items-center justify-between px-3 py-2 text-sm rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ in_array($label['id'], $selectedLabels) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300' }}"
                                     wire:click="toggleLabel('{{ $label['id'] }}')">
                                    <span>{{ $label['name'] }}</span>
                                    @if ($label['messagesTotal'] > 0)
                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">{{ $label['messagesTotal'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Messages List - PRIORITY CONTENT -->
            <div class="lg:col-span-3">
                @if (count($messages))
                    <!-- Mobile-Optimized Email List -->
                    <div class="space-y-3">
                        @foreach ($messages as $message)
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow {{ !$message['isRead'] ? 'border-l-4 border-l-blue-500' : '' }}">
                                <!-- Mobile-First Email Item -->
                                <div class="flex items-start gap-3">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-gradient-to-br {{ $message['isClient'] ? 'from-green-400 to-green-600' : ($message['category'] === 'prospect_inquiry' ? 'from-purple-400 to-purple-600' : 'from-gray-400 to-gray-600') }} flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($message['from_name'] ?: $message['from'], 0, 2)) }}
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Header Row -->
                                        <div class="flex items-start justify-between mb-1">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm lg:text-base truncate">
                                                        {{ $message['from_name'] ?: explode('@', $message['from'])[0] ?? 'Unknown' }}
                                                    </h3>

                                                    <!-- Status badges (mobile-optimized) -->
                                                    @if ($message['isClient'] ?? false)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            Client
                                                        </span>
                                                    @elseif ($message['category'] === 'prospect_inquiry')
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            Lead
                                                        </span>
                                                    @endif

                                                    @if (!$message['isRead'])
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                                                    @endif
                                                </div>

                                                <h4 class="font-medium text-gray-900 dark:text-white text-sm lg:text-base line-clamp-1">
                                                    {{ $message['subject'] }}
                                                </h4>
                                                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 mt-1">
                                                    {{ Str::limit($message['snippet'], 100) }}
                                                </p>
                                            </div>

                                            <!-- Time and Quick Actions -->
                                            <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($message['date'])->format('M j') }}
                                                </span>

                                                <!-- Quick Action Buttons -->
                                                <div class="flex items-center">
                                                    @if (isset($message['isUnread']) && $message['isUnread'])
                                                        <button class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                                                                wire:click="markAsRead('{{ $message['id'] }}')"
                                                                title="Mark as read">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    <button class="p-1 text-gray-400 hover:text-yellow-500"
                                                            wire:click="toggleStar('{{ $message['id'] }}')"
                                                            title="Star">
                                                        <svg class="w-4 h-4" fill="{{ $message['isStarred'] ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action buttons (show on tap/desktop hover) -->
                                        <div class="flex gap-2 mt-3 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                            <button class="px-3 py-1.5 text-xs lg:text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
                                                    wire:click="showEmailPreview('{{ $message['id'] }}')">
                                                View
                                            </button>

                                            @if ($message['category'] === 'prospect_inquiry' || !$message['isClient'])
                                                <button class="px-3 py-1.5 text-xs lg:text-sm font-medium text-green-600 hover:text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 rounded-md hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
                                                        wire:click="createContactFromEmail('{{ $message['id'] }}')">
                                                    Add Contact
                                                </button>
                                            @endif

                                            <button class="px-3 py-1.5 text-xs lg:text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-md hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                                                    wire:click="deleteEmail('{{ $message['id'] }}')">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 lg:p-12 text-center">
                        <svg class="w-12 h-12 lg:w-16 lg:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No messages found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Try adjusting your filters or refresh your inbox.</p>
                        <button wire:click="loadMessages"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Refresh Inbox
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile-Optimized Email Preview Modal -->
    @if ($showingEmailId && $emailPreview)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end lg:items-center justify-center p-0 lg:p-4" wire:click="closeEmailPreview">
            <!-- Mobile: slide up from bottom, Desktop: centered modal -->
            <div class="bg-white dark:bg-gray-800 w-full h-[90vh] lg:h-auto lg:max-h-[90vh] lg:rounded-lg lg:shadow-xl lg:max-w-4xl overflow-hidden" wire:click.stop>
                <!-- Modal Header -->
                <div class="px-4 lg:px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white pr-4 line-clamp-2">{{ $emailPreview['subject'] }}</h3>
                        <button wire:click="closeEmailPreview" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <p class="line-clamp-1"><strong>From:</strong> {{ $emailPreview['from'] }}</p>
                        <p><strong>Date:</strong> {{ $emailPreview['date'] }}</p>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-4 lg:px-6 py-4 h-full overflow-y-auto">
                    <div class="prose dark:prose-invert max-w-none prose-sm lg:prose-base">
                        @if (!empty($emailPreview['body_html'] ?? ''))
                            {!! $emailPreview['body_html'] !!}
                        @elseif (!empty($emailPreview['body_text'] ?? ''))
                            {!! nl2br(e($emailPreview['body_text'])) !!}
                        @else
                            {!! nl2br(e($emailPreview['snippet'])) !!}
                        @endif
                    </div>
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
    </style>
</x-filament::page>