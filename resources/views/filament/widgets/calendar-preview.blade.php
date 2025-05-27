<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-info-500" />
                <span>Calendar</span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::button
                size="xs"
                color="gray"
                href="https://calendar.google.com"
                target="_blank"
                icon="heroicon-m-arrow-top-right-on-square"
            >
                View All
            </x-filament::button>
        </x-slot>

        <div class="space-y-4">
            {{-- Today's Events --}}
            @if (count($todaysEvents) > 0)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Today</h4>
                    <div class="space-y-2">
                        @foreach ($todaysEvents as $event)
                            <div class="flex items-start gap-3 p-2 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium min-w-[60px]">
                                    {{ $event['time'] }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $event['title'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $event['duration'] }}
                                    </p>
                                </div>
                                @if ($event['type'] === 'meeting')
                                    <x-heroicon-o-video-camera class="w-4 h-4 text-gray-400" />
                                @elseif ($event['type'] === 'client')
                                    <x-heroicon-o-user-group class="w-4 h-4 text-gray-400" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        No events scheduled for today
                    </p>
                </div>
            @endif

            {{-- Upcoming Events --}}
            @if (count($upcomingEvents) > 0)
                <div class="pt-3 border-t dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upcoming</h4>
                    <div class="space-y-1">
                        @foreach ($upcomingEvents as $event)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 dark:text-gray-400">{{ $event['date'] }}</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $event['title'] }}</span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $event['time'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
