@if ($stats)
    <div class="rounded-lg border {{ $size === 'compact' ? 'p-3' : 'p-4' }} bg-white dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <x-heroicon-o-envelope class="w-5 h-5 text-primary-500" />
                <span class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $size === 'compact' ? 'Gmail' : 'Gmail Overview' }}
                </span>
            </div>
            @if ($stats['total_unread'] > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                    {{ $stats['total_unread'] }} unread
                </span>
            @endif
        </div>

        @if ($size === 'compact')
            {{-- Compact version - just key numbers --}}
            <div class="grid grid-cols-3 gap-2 text-center">
                <div>
                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $stats['inbox'] }}</div>
                    <div class="text-xs text-gray-500">Inbox</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $stats['important'] }}</div>
                    <div class="text-xs text-gray-500">Important</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $stats['today'] }}</div>
                    <div class="text-xs text-gray-500">Today</div>
                </div>
            </div>
        @else
            {{-- Full version --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['inbox'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Inbox Unread</div>
                </div>
                <div class="text-center p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['important'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Important</div>
                </div>
                @if ($showDetails)
                    <div class="text-center p-3 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['action_required'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Action Required</div>
                    </div>
                    <div class="text-center p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['today'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Today's Emails</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-3 pt-3 border-t dark:border-gray-700">
            <a href="https://mail.google.com"
               target="_blank"
               class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                Open Gmail
                <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
            </a>
        </div>
    </div>
@else
    <div class="rounded-lg border p-4 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
            <span class="font-medium text-gray-600 dark:text-gray-400">Gmail</span>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Connect your Gmail account to see email statistics
        </p>
        <a href="/admin/gmail-integration-page" class="inline-block mt-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
            Connect Gmail →
        </a>
    </div>
@endif