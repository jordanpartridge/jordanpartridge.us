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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Black+Ops+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .military-font {
            font-family: 'Black Ops One', cursive;
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

        .camo-border {
            border: 4px solid transparent;
            border-image: repeating-linear-gradient(
                45deg,
                #4A6741, /* olive drab */
                #4A6741 10px,
                #8A9A80 10px, /* sage */
                #8A9A80 20px,
                #52595D 20px, /* slate gray */
                #52595D 30px
            ) 1;
        }

        .fat-bike-gradient {
            background: linear-gradient(135deg, #2C3539, #576574);
        }

        .snow-texture {
            background-color: #f9f9f9;
            background-image:
              radial-gradient(circle, rgba(255,255,255,0.8) 10%, transparent 10.5%),
              radial-gradient(circle, rgba(255,255,255,0.8) 10%, transparent 10.5%);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
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

        .tire-track {
            position: relative;
        }

        .tire-track::before,
        .tire-track::after {
            content: "";
            position: absolute;
            height: 15px;
            background-image:
                radial-gradient(circle, #000 3px, transparent 4px),
                radial-gradient(circle, #000 3px, transparent 4px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            left: 0;
            right: 0;
            opacity: 0.15;
            z-index: 0;
        }

        .tire-track::before {
            top: 10px;
        }

        .tire-track::after {
            bottom: 10px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/bike-joy-enhancements.css') }}">
</head>
<body>
<x-layouts.marketing>
    @volt('bike')
    <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden fade-in" x-cloak>

        <x-svg-header></x-svg-header>

        <div class="tire-track flex items-center w-full max-w-6xl px-8 pt-12 pb-20 mx-auto bg-gray-100 dark:bg-gray-900">
            <div class="container relative max-w-4xl mx-auto mt-20 text-center sm:mt-24 lg:mt-32 z-10">

                <div class="flex justify-center items-center p-5">
                    <div class="relative rounded-lg overflow-hidden camo-border" style="max-width: 500px;">
                        <x-ui.image-rounded src="/img/FAT-BIKE-DIVISION.png"/>

                    </div>
                </div>


                <div class="m-2 p-2">
                    <div
                        style="background-image:linear-gradient(160deg, #5D8233, #A4B494, #4A6741)"
                        class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                        <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase rounded-full text-slate-800/90 group-hover:text-white/100 military-font">
                            VETERAN BIKE CORPS</p>
                    </div>
                </div>

                <h1 class="military-font text-3xl p-2 leading-normal text-center text-slate-800 dark:text-white sm:text-4xl lg:text-5xl shadow-sm hover:text-green-800 dark:hover:text-green-500 transition-colors duration-300">
                    MISSION: FAT BIKE JOY
                </h1>

                <p class="text-lg mb-8 text-gray-700 dark:text-gray-300">
                    Conquering trails and navigating obstacles with military precision. <br>
                    Fat tires - Because some terrain demands respect.
                </p>

                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:gap-4 xs:grids-cols-2 mb-6">
                        <div class="md:p-6">
                            <label class="block text-slate-800 dark:text-gray-200 mb-2 military-font" for="startDate">
                                MISSION START DATE</label>
                            <input aria-label="start date"
                                   class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-green-500 dark:focus:border-green-500 focus:outline-none transition-colors duration-300"
                                   type="date" id="startDate" name="startDate" wire:model="startDate"
                                   wire:change="recalculateMetrics">
                        </div>
                        <div class="md:p-6">
                            <label class="block text-slate-800 dark:text-gray-200 mb-2 military-font" for="endOfWeek">
                                MISSION END DATE</label>
                            <input aria-label="end date"
                                   class="text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800 rounded-lg p-3 w-full mb-4 border-2 border-gray-300 dark:border-gray-600 focus:border-green-500 dark:focus:border-green-500 focus:outline-none transition-colors duration-300"
                                   type="date" id="endOfWeek" name="endOfWeek" wire:model="endDate"
                                   wire:change="recalculateMetrics">
                        </div>
                    </div>

                    <div class="w-full col-span-1 md:col-span-2 lg:col-span-3">
                        <h3 class="text-xl font-bold text-center mb-4 military-font text-gray-800 dark:text-gray-200">MISSION STATS</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">🚴‍♂️</span> DISTANCE
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['distance'] }}
                                            miles</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Additional cards -->
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">🔥</span> CALORIES
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['calories'] }}
                                            calories</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">🏔️</span> ELEVATION
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['elevation'] }}
                                            ft</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">📅</span> DEPLOYMENTS
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['ride_count'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">⚡</span> MAX SPEED
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['max_speed'] }}
                                            mph</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="fat-bike-gradient rounded-xl shadow-lg p-4 hover:shadow-xl hover:scale-105 transition-transform duration-300 camo-border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white military-font">
                                            <span class="emoji">🏎️</span> AVG SPEED
                                        </h3>
                                        <p class="text-white text-lg special-text">{{ $this->metrics['average_speed'] }}
                                            mph</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fat Bike Creed Section -->
                <div class="mt-12 p-6 bg-gray-800 text-white rounded-lg camo-border creed-container">
    <div class="creed-background"></div>
    <div class="creed-content">
        <h3 class="text-2xl font-bold mb-4 military-font military-section-header">THE FAT BIKER'S CREED</h3>
        <p class="italic text-lg">
            This is my fat bike. There are many like it, but this one is mine.<br>
            My fat bike is my best friend. It is my life.<br>
            Without me, my fat bike is useless. Without my fat bike, I am without joy.<br>
            I will ride my fat bike true. I will conquer snow, sand, and mud that hinders other bikes.<br>
            I will...
        </p>
    </div>
</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 snow-texture">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center military-font">
                    RECENT DEPLOYMENT LOGS
                </h2>
                @if (count($this->rides) > 0)
    @foreach ($this->rides as $ride)
        <x-bike-joy.ride :ride="$ride"/>
    @endforeach
@else
    <div class="empty-deployment-logs">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-2">No recent deployments recorded</p>
        <p class="text-gray-500 dark:text-gray-500 text-sm text-center">Plan your next mission using the date selectors above</p>
    </div>
@endif
            </div>
        </div>

        <!-- Fat Bike Perks Section -->
        <div class="bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center military-font">
                    FAT BIKE ADVANTAGES
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md camo-border">
                        <div class="flex items-center mb-4">
                            <span class="text-3xl mr-3">🌨️</span>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white military-font">SNOW DOMINATION</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            Where other bikes surrender, fat bikes conquer. Those 4.5"+ tires float over snow like a tank with tracks.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md camo-border">
                        <div class="flex items-center mb-4">
                            <span class="text-3xl mr-3">🏜️</span>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white military-font">ALL-TERRAIN</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            Sand, mud, rocks, roots - the fat bike treats them all as minor obstacles. Ride anywhere, anytime, in any conditions.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md camo-border">
                        <div class="flex items-center mb-4">
                            <span class="text-3xl mr-3">💪</span>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white military-font">STRENGTH BUILDER</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            These aren't your carbon fiber race machines. The extra weight and resistance builds character and muscle. Embrace the pain.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 mt-8 w-full">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center military-font">
                    MISSION SOUNDTRACK
                </h2>
                <div class="playlist-container camo-border">
                    <iframe style="border-radius:12px"
                            src="https://open.spotify.com/embed/playlist/0MUayKfGRk0kRvsaQBDBWe?utm_source=generator&theme=0"
                            width="100%" height="352" frameBorder="0" allowfullscreen=""
                            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                            loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
