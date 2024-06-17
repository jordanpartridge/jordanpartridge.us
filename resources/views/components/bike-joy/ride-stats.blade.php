@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'w-full flex flex-col justify-center items-center' : 'lg:w-1/3 flex flex-col justify-between' }}">
    <div class="text-center mb-2">
        <div class="flex flex-col items-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $ride->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                <span class="text-lg text-blue-500">{{ $ride->icon }}</span>
                <span>{{ $ride->rideDiff }}</span>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                <span class="text-lg text-green-500">📏</span>
                <span>{{ $ride->distance . ' miles' }}</span>
            </p>
        </div>
    </div>

    @unless ($condense)
        <div class="mt-4 space-y-2">
            @foreach([
                        'Distance' => [$ride->distance . ' miles', '🚴'],
                        'Duration' => [$ride->moving_time, '⏱️'],
                        'Elevation' => [$ride->elevation . ' ft', '🏔️'],
                        'Calories Burned' => [$ride->calories . ' kcal', '🔥'],
                        'Avg Speed' => [$ride->average_speed . ' mph', '⚡'],
                        'Max Speed' => [$ride->max_speed . ' mph', '🏎️'],
                    ] as $label => [$value, $icon])
                <x-bike-joy.ride-stat :icon="$icon" :label="$label" :value="$value"/>
            @endforeach
        </div>
    @endunless
</div>
