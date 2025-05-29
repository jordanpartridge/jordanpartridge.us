<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-flag class="w-5 h-5 text-warning-500" />
                <span>Today's Focus</span>
            </div>
        </x-slot>

        <div class="space-y-3">
            {{-- Priority Items --}}
            @if ($priorities->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($priorities as $priority)
                        <div class="group relative flex items-start gap-3 p-3 rounded-lg border dark:border-gray-700 hover:border-{{ $priority['color'] }}-500 dark:hover:border-{{ $priority['color'] }}-400 transition-all">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-8 h-8 rounded-full bg-{{ $priority['color'] }}-100 dark:bg-{{ $priority['color'] }}-900/30 flex items-center justify-center">
                                    <x-dynamic-component
                                        :component="'heroicon-o-' . str_replace('heroicon-o-', '', $priority['icon'])"
                                        class="w-4 h-4 text-{{ $priority['color'] }}-600 dark:text-{{ $priority['color'] }}-400"
                                    />
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2">
                                    {{ $priority['title'] }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $priority['context'] }}
                                </p>
                            </div>

                            @if ($priority['action'])
                                <a href="{{ $priority['action'] }}"
                                   @if (str_contains($priority['action'], 'http'))
                                       target="_blank"
                                   @endif
                                   class="opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                    <x-filament::icon-button
                                        icon="heroicon-m-arrow-top-right-on-square"
                                        size="sm"
                                        color="gray"
                                    />
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-check-circle class="mx-auto w-12 h-12 text-success-400 mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Clear schedule ahead! Time to tackle something new.
                    </p>
                </div>
            @endif

            {{-- Smart Suggestions --}}
            @if (count($suggestions) > 0)
                <div class="pt-3 border-t dark:border-gray-700">
                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Suggestions
                    </h4>
                    <div class="space-y-2">
                        @foreach ($suggestions as $suggestion)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <x-dynamic-component
                                    :component="'heroicon-o-' . str_replace('heroicon-o-', '', $suggestion['icon'])"
                                    class="w-4 h-4 text-gray-400 dark:text-gray-500"
                                />
                                <span>{{ $suggestion['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
