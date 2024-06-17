<?php


?>

<div class="w-full lg:w-2/3 flex flex-col justify-between">
    <div class="flex justify-center items-center mb-4">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center">{{ $ride->name }}</h2>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 text-center">
        {{Carbon::parse($ride->date)->diffForHumans() }}
    </p>
    <div class="mt-6 space-y-4">
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
</div>
