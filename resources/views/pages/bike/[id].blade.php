@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};
    use App\Models\Ride;

    name('bike.ride');
    middleware(['web']);
@endphp

@volt
<?php
$ride = Ride::findOrFail($id);
?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-semibold mb-2">{{ $ride->name }}</h1>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $ride->date->format('F j, Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-900 px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Distance</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ $ride->distance_mi }} mi</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-900 px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Time</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ $ride->moving_time_formatted }}</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-900 px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Avg Speed</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ $ride->average_speed_mph }} mph</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-900 px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Elevation</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ $ride->elevation_ft }} ft</dd>
                        </div>
                    </div>

                    @if ($ride->description)
                        <div class="mt-8 prose dark:prose-invert max-w-none">
                            {!! $ride->description !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt