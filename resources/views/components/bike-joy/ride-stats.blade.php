@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'w-full' : 'w-full lg:w-2/3' }} bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg camo-border">
    <div class="p-4">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1 military-font">MISSION: {{ $ride->name }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="inline-block bg-green-800 text-white rounded-sm px-3 py-1 text-xs font-semibold mr-2 military-font">
                    TERRAIN: {{ $ride->rideDiff }}
                </span>
                <span class="inline-block bg-gray-700 text-white rounded-sm px-3 py-1 text-xs font-semibold military-font">
                    DISTANCE: {{ $ride->distance }} miles
                </span>
            </p>
        </div>

        @unless ($condense)
            <div class="mt-4 space-y-2">
                @foreach ([
                    'OPERATION TIME' => [$ride->moving_time, '⏱️'],
                    'ELEVATION GAIN' => [$ride->elevation . ' ft', '🏔️'],
                    'CALORIES EXPENDED' => [$ride->calories . ' kcal', '🔥'],
                    'CRUISING VELOCITY' => [$ride->average_speed . ' mph', '⚡'],
                    'TOP SPEED' => [$ride->max_speed . ' mph', '🏎️'],
                ] as $label => [$value, $icon])
                    <div class="flex items-center justify-between p-3 fat-bike-gradient rounded-lg transition-all duration-300 hover:bg-gray-600 border border-gray-700">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $icon }}</span>
                            <span class="text-sm font-medium text-white military-font">{{ $label }}</span>
                        </div>
                        <span class="font-semibold text-white">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @endunless
    </div>
</div>
