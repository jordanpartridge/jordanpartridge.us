@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'w-full' : 'w-full lg:w-2/3' }} bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
    <div class="p-4">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $ride->name }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full px-2 py-1 text-xs font-semibold mr-2">
                    {{ $ride->rideDiff }}
                </span>
                <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full px-2 py-1 text-xs font-semibold">
                    {{ $ride->distance }} miles
                </span>
            </p>
        </div>

        @unless ($condense)
            <div class="mt-4 space-y-2">
                @foreach ([
                    'Duration' => [$ride->moving_time, '⏱️'],
                    'Elevation' => [$ride->elevation . ' ft', '🏔️'],
                    'Calories Burned' => [$ride->calories . ' kcal', '🔥'],
                    'Avg Speed' => [$ride->average_speed . ' mph', '⚡'],
                    'Max Speed' => [$ride->max_speed . ' mph', '🏎️'],
                ] as $label => [$value, $icon])
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $icon }}</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @endunless
    </div>
</div>
