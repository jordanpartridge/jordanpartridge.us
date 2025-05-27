<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-envelope class="w-5 h-5 text-primary-500" />
                    <span>Email Priorities</span>
                </div>
                @if ($totalUnread > 0)
                    <x-filament::badge color="danger">
                        {{ $totalUnread }} unread
                    </x-filament::badge>
                @endif
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::button
                size="xs"
                color="gray"
                href="https://mail.google.com"
                target="_blank"
                icon="heroicon-m-arrow-top-right-on-square"
            >
                Gmail
            </x-filament::button>
        </x-slot>

        <div class="space-y-3">
            {{-- Quick Stats --}}
            <div class="grid grid-cols-3 gap-2 pb-3 border-b dark:border-gray-700">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                        {{ $unreadCounts->get('inbox', 0) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Inbox</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-warning-600 dark:text-warning-400">
                        {{ $unreadCounts->get('important', 0) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Important</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-danger-600 dark:text-danger-400">
                        {{ $unreadCounts->get('action_required', 0) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Action</div>
                </div>
            </div>

            {{-- Priority Emails --}}
            @if ($priorityEmails->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($priorityEmails as $email)
                        <div class="group relative p-3 rounded-lg border dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-400 transition-all cursor-pointer"
                             wire:click="markAsRead('{{ $email['id'] }}')"
                        >
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">
                                            {{ $email['from'] }}
                                        </h4>
                                        @if ($email['labels'])
                                            <div class="flex gap-1">
                                                @foreach ($email['labels'] as $label)
                                                    <x-filament::badge size="xs" :color="$label === 'Important' ? 'warning' : 'gray'">
                                                        {{ $label }}
                                                    </x-filament::badge>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 line-clamp-1">
                                        {{ $email['subject'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-1">
                                        {{ $email['snippet'] }}
                                    </p>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $email['time'] }}
                                </div>
                            </div>

                            {{-- Quick Actions (shown on hover) --}}
                            <div class="absolute inset-y-0 right-2 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <x-filament::icon-button
                                    icon="heroicon-m-check"
                                    size="sm"
                                    color="success"
                                    wire:click.stop="markAsRead('{{ $email['id'] }}')"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="mx-auto w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Inbox Zero! All caught up 🎉
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
