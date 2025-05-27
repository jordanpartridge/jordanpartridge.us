@props([
    'message',
    'isGitHubNotification' => false,
    'isGitHubPR' => false,
    'isGitHubIssue' => false,
    'isGitHubAction' => false,
    'isLaravel' => false,
    'isServiceNotification' => false,
    'githubUrls' => []
])

<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-600 p-6 lg:p-8 hover:shadow-xl dark:hover:shadow-gray-900/20 transition-all duration-300 group {{ !($message['isRead'] ?? true) ? 'border-l-4 border-l-blue-500 bg-blue-50/30 dark:bg-blue-950/30' : '' }}">
    <!-- Email Header -->
    <div class="flex items-start gap-4 lg:gap-6 mb-6">
        <!-- Enhanced Avatar -->
        <div class="w-12 h-12 lg:w-16 lg:h-16 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0 relative
                    {{ $isGitHubNotification ? 'bg-gradient-to-br from-gray-800 to-black' :
                       (($message['isClient'] ?? false) ? 'bg-gradient-to-br from-green-400 to-green-600' :
                       (($message['category'] ?? '') === 'prospect_inquiry' ? 'bg-gradient-to-br from-purple-400 to-purple-600' :
                       ($isLaravel ? 'bg-gradient-to-br from-red-500 to-red-700' :
                       'bg-gradient-to-br from-gray-400 to-gray-600'))) }}">
            @if ($isGitHubNotification)
                <x-gmail.icons.github class="w-8 h-8 lg:w-10 lg:h-10" />
            @else
                {{ strtoupper(substr($message['from_name'] ?? $message['from'] ?? '', 0, 2)) }}
            @endif

            <!-- Special notification indicators -->
            @if ($isGitHubPR)
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-xs font-bold">PR</div>
            @elseif ($isGitHubAction)
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center text-xs font-bold">CI</div>
            @elseif ($isGitHubIssue)
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold">!</div>
            @endif
        </div>

        <!-- Email Content -->
        <div class="flex-1 min-w-0">
            <!-- Header with sender and badges -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                        <h3 class="font-bold text-gray-900 dark:text-white text-base lg:text-lg truncate">
                            {{ $message['from_name'] ?? explode('@', $message['from'] ?? '')[0] ?? 'Unknown' }}
                        </h3>

                        <!-- Status badges -->
                        <div class="flex items-center gap-2 flex-wrap">
                            @if ($isGitHubNotification)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-900 text-white dark:bg-gray-200 dark:text-gray-900 shadow-sm">
                                    <x-gmail.icons.github class="w-3 h-3 mr-1" />
                                    GitHub
                                </span>
                            @endif

                            @if ($isLaravel)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 shadow-sm">
                                    Laravel
                                </span>
                            @endif

                            @if ($message['isClient'] ?? false)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 shadow-sm">
                                    Client
                                </span>
                            @elseif (($message['category'] ?? '') === 'prospect_inquiry')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 shadow-sm">
                                    Lead
                                </span>
                            @endif

                            @if ($isServiceNotification)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                                    Automated
                                </span>
                            @endif

                            @if (!($message['isRead'] ?? true))
                                <span class="w-3 h-3 bg-blue-500 dark:bg-blue-400 rounded-full flex-shrink-0 shadow-sm"></span>
                            @endif
                        </div>
                    </div>

                    <!-- Subject -->
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm lg:text-base line-clamp-2 mb-3">
                        {{ $message['subject'] ?? 'No Subject' }}
                    </h4>

                    <!-- Snippet -->
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base line-clamp-3 mb-4 leading-relaxed">
                        {{ Str::limit($message['snippet'] ?? '', 150) }}
                    </p>

                    <!-- Email metadata -->
                    <div class="flex items-center gap-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            {{ $message['from'] ?? 'Unknown' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ isset($message['date']) ? \Carbon\Carbon::parse($message['date'])->format('M j, Y \a\t g:i A') : 'Unknown date' }}
                        </span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                    @if (!($message['isRead'] ?? true))
                        <button class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all"
                                wire:click="markAsRead('{{ $message['id'] ?? '' }}')"
                                title="Mark as read">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    @endif

                    <button class="p-2 text-gray-400 hover:text-yellow-500 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-all"
                            wire:click="toggleStar('{{ $message['id'] ?? '' }}')"
                            title="Star">
                        <svg class="w-5 h-5" fill="{{ ($message['isStarred'] ?? false) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Action Section -->
    <div class="opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-300 border-t border-gray-100 dark:border-gray-700 pt-6">
        <x-gmail.email-actions
            :message="$message"
            :is-git-hub-notification="$isGitHubNotification"
            :github-urls="$githubUrls" />
    </div>
</div>