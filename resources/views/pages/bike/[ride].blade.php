<?php

use function Livewire\Volt\{state};

state(['ride' => fn () => BikeRide::findOrFail($ride)]);

?>

<x-app-layout>
    <div>
        <!-- Individual Ride Content -->
        <h1>{{ $ride->title }}</h1>
        <!-- Ride details -->
    </div>
</x-app-layout>