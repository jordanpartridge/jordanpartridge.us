@props([
    'metrics' => []
])

<div class="w-full col-span-1 md:col-span-2 lg:col-span-3">
    <h3 class="text-xl font-bold text-center mb-4 military-font text-gray-800 dark:text-gray-200">MISSION STATS</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
        <x-bike-corps.stat-card
            icon="🚴‍♂️"
            title="DISTANCE"
            value="{{ $metrics['distance'] }}"
            unit="miles"
        />

        <x-bike-corps.stat-card
            icon="🔥"
            title="CALORIES"
            value="{{ $metrics['calories'] }}"
            unit="calories"
        />

        <x-bike-corps.stat-card
            icon="🏔️"
            title="ELEVATION"
            value="{{ $metrics['elevation'] }}"
            unit="ft"
        />

        <x-bike-corps.stat-card
            icon="📅"
            title="DEPLOYMENTS"
            value="{{ $metrics['ride_count'] }}"
            unit=""
        />

        <x-bike-corps.stat-card
            icon="⚡"
            title="MAX SPEED"
            value="{{ $metrics['max_speed'] }}"
            unit="mph"
        />

        <x-bike-corps.stat-card
            icon="🏎️"
            title="AVG SPEED"
            value="{{ $metrics['average_speed'] }}"
            unit="mph"
        />
    </div>
</div>