<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-heart class="w-5 h-5 text-danger-500" />
                <span>Fitness Tracker</span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::button
                size="xs"
                color="gray"
                :href="route('filament.admin.resources.rides.index')"
                icon="heroicon-m-chart-bar"
            >
                All Rides
            </x-filament::button>
        </x-slot>

        <div class="space-y-4">
            {{-- Last Ride Status --}}
            @php
                $status = $this->getRideStatus();
            @endphp
            <div class="p-4 rounded-lg bg-{{ $status['color'] }}-50 dark:bg-{{ $status['color'] }}-900/20 border border-{{ $status['color'] }}-200 dark:border-{{ $status['color'] }}-800">
                <div class="flex items-center gap-3">
                    <x-dynamic-component
                        :component="'heroicon-o-' . str_replace('heroicon-o-', '', $status['icon'])"
                        class="w-8 h-8 text-{{ $status['color'] }}-600 dark:text-{{ $status['color'] }}-400"
                    />
                    <div class="flex-1">
                        <p class="font-medium text-{{ $status['color'] }}-900 dark:text-{{ $status['color'] }}-100">
                            {{ $status['message'] }}
                        </p>
                        @if ($lastRide)
                            <p class="text-sm text-{{ $status['color'] }}-700 dark:text-{{ $status['color'] }}-300">
                                Last ride: {{ $timeSinceLastRide }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Weekly Progress --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Weekly Goal Progress</h4>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $weeklyStats['distance'] }}/20 miles
                    </span>
                </div>
                <div class="relative">
                    <div class="overflow-hidden h-2 text-xs flex rounded-full bg-gray-200 dark:bg-gray-700">
                        <div style="width: {{ $weeklyGoalProgress }}%"
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500 transition-all duration-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Stats Grid --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                        {{ $currentStreak }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Day Streak</div>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div class="text-2xl font-bold text-success-600 dark:text-success-400">
                        {{ $weeklyStats['rides'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Rides This Week</div>
                </div>
            </div>

            {{-- This Week Summary --}}
            <div class="pt-3 border-t dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">This Week</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Distance:</span>
                        <span class="font-medium">{{ $weeklyStats['distance'] }} mi</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Time:</span>
                        <span class="font-medium">{{ $weeklyStats['time'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Elevation:</span>
                        <span class="font-medium">{{ number_format($weeklyStats['elevation']) }} ft</span>
                    </div>
                </div>
            </div>

            {{-- Monthly Projection --}}
            @if ($monthlyProgress['rides'] > 0)
                <div class="pt-3 border-t dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Projection</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        At current pace: {{ $monthlyProgress['projected'] }} rides by month end
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
