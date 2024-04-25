@php use Illuminate\Support\Carbon; @endphp
<div class=" bg-gray-50 dark:bg-gray-900 flex flex-col md:flex-row items-center justify-center hover:saturate-200">
    <!-- Map Section with Updated Label -->
    <div class="relative group w-full md:w-64 md:h-64 hover:scale-105">
        <div class="bg-white dark:bg-gray-800 rounded-full shadow-lg overflow-hidden w-full h-64 md:w-full md:h-full">
            <img
                src="https://maps.googleapis.com/maps/api/staticmap?size=9000x9000&maptype=roadmap&path=enc:{{$ride->polyline}}&key={{config('services.google_maps.key')}}"
                alt="Route Map"
                class="absolute inset-0 w-full h-full object-cover transform  transition-transform duration-700 ease-in-out">
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-center py-2 hover:z-50">
                <span class="font-bold">{{$ride->name}} - {{$ride->distance}} miles</span>
                <svg class="dark:text-gray-400 -mt-0.5 h-3.5 inline-block w-3.5" data-icon="tabler:clock" height="1em"
                     viewBox="0 0 24 24" width="1em">
                    <symbol id="ai:tabler:clock">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                           stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0"></path>
                            <path d="M12 7v5l3 3"></path>
                        </g>
                    </symbol>
                    <use xlink:href="#ai:tabler:clock"></use>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Section with Responsive Design -->
    <div class="bg-white dark:bg-gray-800 rounded-xl items-center shadow-lg p-4 w-full md:max-w-md">
        <h1 class="text-2xl font-bold bg-slate-500 hover:bg-blue-300 text-amber-50 dark:text-white dark:bg-gray-700">{{Carbon::parse($ride->date)->diffForHumans()}}</h1>
        <div class="space-y-2">
            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Duration</span>
                <span class="font-semibold">{{$ride->moving_time}} hrs</span>
            </div>
            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Elevation</span>
                <span class="font-semibold">{{$ride->elevation}} ft</span>
            </div>
            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Calories Burned</span>
                <span class="font-semibold">{{$ride->calories}} kcal</span>
            </div>
            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Avg Speed</span>
                <span class="font-semibold">{{$ride->average_speed}} mph</span>
            </div>
            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Max Speed</span>
                <span class="font-semibold">{{$ride->max_speed}} mph</span>
            </div>
        </div>
    </div>
</div>
