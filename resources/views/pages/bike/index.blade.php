@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('bike.index');
    middleware(['web']);
@endphp

@volt
<?php

$rides = []; // Initialize empty array for now - this should come from your data source

?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Bike Rides</h1>
                    @if (count($rides) > 0)
                        <div class="grid gap-4">
                            @foreach ($rides as $ride)
                                <div class="border dark:border-gray-700 p-4 rounded-lg">
                                    <div>
                                        <!-- Ride details will go here -->
                                        Ride Details
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No rides found.</p>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt