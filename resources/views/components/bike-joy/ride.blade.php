@php use Illuminate\Support\Carbon; @endphp
<div class="w-full m-4 mb-0">
    <div class="bg-gray-50 dark:bg-gray-900 p-8 rounded-2xl shadow-lg transition-shadow duration-300 flex flex-col lg:flex-row">
        <!-- Map Section -->
        <div class="relative w-full lg:w-1/3 mb-4 lg:mb-0 lg:mr-6">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden w-full h-64 lg:h-full shadow-lg transition-transform duration-300 transform hover:scale-105">
                <img
                    src="https://maps.googleapis.com/maps/api/staticmap?size=3000x3000&maptype=roadmap&path=enc:{{$ride->polyline}}&key={{ config('services.google_maps.key') }}&center={{ $ride->start_lat }},{{ $ride->start_lng }}"
                    alt="Route Map"
                    class="w-full h-full object-cover rounded-lg">
            </div>
        </div>
        <!-- Stats Section -->
        <div class="w-full lg:w-2/3 flex flex-col justify-between">
            <div class="flex justify-center items-center mb-4">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center">{{ $ride->name }}</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 text-center">{{ Carbon::parse($ride->date)->diffForHumans() }}</p>
            <div class="mt-6 space-y-4">
                @foreach([
                    'Distance' => [$ride->distance . ' miles', 'icon-distance'],
                    'Duration' => [$ride->moving_time, 'icon-duration'],
                    'Elevation' => [$ride->elevation . ' ft', 'icon-elevation'],
                    'Calories Burned' => [$ride->calories . ' kcal', 'icon-calories'],
                    'Avg Speed' => [$ride->average_speed . ' mph', 'icon-speed'],
                    'Max Speed' => [$ride->max_speed . ' mph', 'icon-speed-max']
                ] as $label => [$value, $icon])
                    <div class="flex items-center justify-between text-gray-700 dark:text-gray-300 p-4 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 shadow-md transition-transform duration-300 transform hover:scale-105">
                        <div class="flex items-center">
                            <span class="text-xl">{{ $icon == 'icon-distance' ? '🚴' : ($icon == 'icon-duration' ? '⏱️' : ($icon == 'icon-elevation' ? '🏔️' : ($icon == 'icon-calories' ? '🔥' : ($icon == 'icon-speed' ? '⚡' : '🏎️')))) }}</span>
                            <span class="ml-2">{{ $label }}</span>
                        </div>
                        <span class="font-semibold">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional custom styles for enhanced design */
    .shadow-lg:hover {
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.5);
    }
    .transition-transform {
        transition: transform 0.3s ease-in-out;
    }
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
</style>
