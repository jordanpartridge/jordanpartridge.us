@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'w-full flex flex-col items-center' : 'lg:w-2/3 flex flex-col justify-between' }}">
    <div class="text-center mb-2">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $ride->name }}</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ride->rideDiff }} - {{ $ride->distance }} miles</p>
    </div>

    @unless ($condense)
        <div class="mt-4 space-y-2">
            @foreach([
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
