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

<x-layouts.marketing>
    <!-- Add structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Fat Bike Corps - Laravel Strava Integration",
        "applicationCategory": "SportsApplication",
        "operatingSystem": "Any",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "description": "Military-themed fat bike activity tracker powered by Laravel Strava Client package",
        "author": {
            "@type": "Person",
            "name": "Jordan Partridge"
        }
    }
    </script>

    @volt('bike')
    <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden fade-in" x-cloak>
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
                    MISSION: FAT BIKE DIVISION
                </h1>

                <p class="text-lg mb-4 text-gray-700 dark:text-gray-300">
                    Conquering trails and navigating obstacles with military precision. <br>
                    Fat tires - Because some terrain demands respect. <br>
                    <span class="italic">You don't have to be fat to ride a fat bike, but it sure doesn't hurt the brand.</span>
                </p>

                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <a href="https://github.com/jordanpartridge/strava-client" target="_blank" rel="noopener noreferrer" aria-label="View Laravel Strava Client package on GitHub" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V19c0 .27.16.59.67.5C17.14 18.16 20 14.42 20 10A10 10 0 0010 0z" clip-rule="evenodd" />
                        </svg>
                        Laravel Strava Client Package on GitHub
                    </a>
                    <a href="/strava-client" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Strava Client Package Documentation
                    </a>
                </div>

                <div>
                    <x-bike-corps.date-range-selector
                        :startDate="$this->startDate"
                        :endDate="$this->endDate"
                    />

                    <x-bike-corps.stats-dashboard :metrics="$this->metrics" />
                </div>

                <!-- Fat Bike Creed Section -->
                <x-bike-corps.creed
                    title="FAT BIKE DIVISION CREED"
                    content="This is my fat bike. There are many like it, but this one is mine.
My fat bike is my best friend. It is my life.
Without me, my fat bike is useless. Without my fat bike, I am without joy.
I will ride my fat bike true. I will conquer snow, sand, and mud that hinders other bikes.
I will..."
                />
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

        <!-- Fat Bike Tactical Advantages Section -->
        <div class="bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center military-font">
                    TACTICAL ADVANTAGES
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-bike-corps.field-advantage
                        icon="🌨️"
                        title="SNOW DOMINATION"
                        description="Where other bikes surrender, fat bikes conquer. Those 4.5&quot;+ tires float over snow like a tank with tracks."
                    />

                    <x-bike-corps.field-advantage
                        icon="🏜️"
                        title="ALL-TERRAIN"
                        description="Sand, mud, rocks, roots - the fat bike treats them all as minor obstacles. Ride anywhere, anytime, in any conditions."
                    />

                    <x-bike-corps.field-advantage
                        icon="💪"
                        title="STRENGTH BUILDER"
                        description="These aren't your carbon fiber race machines. The extra weight and resistance builds character and muscle. Embrace the pain."
                    />
                </div>
            </div>
        </div>

        <!-- Strava Integration Case Study Section -->
        <div class="bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center military-font">
                    MISSION INTELLIGENCE SYSTEM
                </h2>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 camo-border">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/2">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Tactical Strava Integration</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                The FAT BIKE DIVISION utilizes advanced field intelligence systems through
                                the <a href="https://developers.strava.com/docs/reference/" class="text-primary-500 hover:text-primary-700 underline" target="_blank" rel="noopener noreferrer">Strava API</a>. This page syncs and displays real ride data using my custom-built
                                <a href="/strava-client" class="text-primary-500 hover:text-primary-700 underline">Laravel Strava Client package</a>,
                                which handles OAuth authentication, token management, and activity syncing with elegant Laravel-friendly syntax.
                            </p>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">OAuth Authentication</h4>
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">Secure token management and automatic refresh</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">Activity Syncing</h4>
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">Automated background syncing of ride data</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">Metric Processing</h4>
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">Smart caching and aggregation of ride metrics</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Buttons moved to top of page -->
                        </div>
                        <div class="w-full md:w-1/2 mt-6 md:mt-0">
                            <div class="bg-gray-800 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center mb-3">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <div class="ml-2 text-xs text-gray-400">strava-client-example.php</div>
                                </div>
                                <pre class="text-xs md:text-sm text-gray-300 overflow-x-auto"><code>// Import Strava facade
use JordanPartridge\StravaClient\Facades\Strava;

// Fetch recent activities with authentication handled behind the scenes
$activities = Strava::activities()
    ->after(now()->subDays(30))
    ->before(now())
    ->perPage(50)
    ->get();

// Process activities
foreach ($activities as $activity) {
    Ride::updateOrCreate(
        ['external_id' => $activity['id']],
        [
            'name' => $activity['name'],
            'distance' => $activity['distance'],
            'moving_time' => $activity['moving_time'],
            'elevation' => $activity['total_elevation_gain'],
            'date' => Carbon::parse($activity['start_date']),
            'average_speed' => $activity['average_speed'],
            'max_speed' => $activity['max_speed'],
            'polyline' => $activity['map']['summary_polyline'] ?? null,
        ]
    );
}

// Calculate aggregated metrics
$metrics = app(RideMetricService::class)
    ->calculateRideMetrics($startDate, $endDate);</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 mt-8 w-full">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center military-font">
                    FIELD OPERATIONS SOUNDTRACK
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
