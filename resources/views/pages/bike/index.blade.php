<?php

use Carbon\Carbon;
use App\Services\RideMetricService;

use function Livewire\Volt\{mount, state};

state([
    'startDate' => Carbon::now()->subDays(7)->format('Y-m-d'),
    'endDate'   => Carbon::now()->format('Y-m-d'),
    'rides'     => [],
    'metrics'   => [],
]);


$recalculateMetrics = function (RideMetricService $service) {
    list(
        $this->rides,
        $this->metrics,
        $this->startDate,
        $this->endDate
    ) = $service->calculateRideMetrics($this->startDate, $this->endDate);
};

mount(function (RideMetricService $service) {
    $this->recalculateMetrics($service);
});


?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .fade-in {
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .gradient-border {
            border-image-slice: 1;
            border-width: 3px;
            border-image-source: linear-gradient(45deg, #f3ec78, #af4261);
        }

        .playlist-container {
            background-color: #4b5563; /* Tailwind's gray-700 */
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .playlist-container:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body>
<x-layouts.marketing>
    @volt('bike')
    <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden fade-in" x-cloak>
        <x-svg-header></x-svg-header>

        <div class="flex items-center w-full max-w-6xl px-8 pt-12 pb-20 mx-auto">
            <div class="container relative max-w-4xl mx-auto mt-20 text-center sm:mt-24 lg:mt-32">

                <div class="flex justify-center items-center p-5">
                    <x-ui.image-rounded src="/img/bike-joy.jpg"/>
                </div>
                <div class="m-2 p-2">
                    <div
                        style="background-image:linear-gradient(160deg,rgba(20,208,136,0.43),#3566e3 50%,#73f4f8, #110e0f)"
                        class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                        <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase rounded-full text-slate-800/90 group-hover:text-white/100">
                            Bike Joy</p>
                    </div>
                </div>

                <h1 class="text-3xl p-2 font-normal leading-normal text-center text-slate-800 dark:text-white sm:text-4xl lg:text-5xl shadow-sm hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                    Everyone is entitled to bike joy
                </h1>
                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:gap-4 xs:grids-cols-2 mb-6">
                        <div class="md:p-6">
                            <label class="block text-slate-800 dark:text-gray-200 mb-2" for="startDate">Start
                                Date</label>
                            <input aria-label="start date"
                                   class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none transition-colors duration-300"
                                   type="date" id="startDate" name="startDate" wire:model="startDate"
                                   wire:change="recalculateMetrics">
                        </div>
                        <div class="md:p-6">
                            <label class="block text-slate-800 dark:text-gray-200 mb-2" for="endOfWeek">End Date</label>
                            <input aria-label="end date"
                                   class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none transition-colors duration-300"
                                   type="date" id="endOfWeek" name="endOfWeek" wire:model="endDate"
                                   wire:change="recalculateMetrics">
                        </div>
                    </div>
                    <div class="w-full col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">🚴‍♂️</span> Distance
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['distance'] }}
                                            miles</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Additional cards -->
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">🔥</span> Calories
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['calories'] }}
                                            calories</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">🏔️</span> Elevation
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['elevation'] }}
                                            ft</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">📅</span> Number of rides
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['ride_count'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">⚡</span> Max Speed
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['max_speed'] }}
                                            mph</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-600 dark:bg-gray-800 rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 gradient-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            <span class="emoji">🏎️</span> Average Speed
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['average_speed'] }}
                                            mph</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                    Check Out My Recent Rides!
                </h2>
                @foreach ($this->rides as $ride)
                    <x-bike-joy.ride :ride="$ride"/>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 mt-8 w-full">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                    Listen to My Playlist!
                </h2>
                <div class="playlist-container">
                    <iframe style="border-radius:12px"
                            src="https://open.spotify.com/embed/playlist/0MUayKfGRk0kRvsaQBDBWe?utm_source=generator&theme=0"
                            width="100%" height="352" frameBorder="0" allowfullscreen=""
                            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                            loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    @endvolt
</x-layouts.marketing>

