@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'condense' => false,
    'ride' => null,
])

<div class="{{ $condense ? 'm-0 mb-0' : 'm-4 mb-0' }}">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-shadow duration-300 flex flex-col {{ $condense ? 'p-0 m-0' : 'lg:flex-row p-6' }}">
        <!-- Map Section -->
        <x-route-map :ride="$ride" :condense="$condense"/>

        <!-- Stats Section -->
        <x-bike-joy.ride-stats :ride="$ride" :condense="$condense"/>
    </div>
</div>
