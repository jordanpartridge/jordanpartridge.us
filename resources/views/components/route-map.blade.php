@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'relative w-full mb-4' : 'relative lg:w-1/3 mb-4 lg:mb-0 lg:mr-6' }}">
    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden w-full h-64 lg:h-full shadow-lg transition-transform duration-300 transform hover:scale-105">
        <img
            src="https://maps.googleapis.com/maps/api/staticmap?size=3000x3000&maptype=roadmap&path=enc:{{$ride->polyline}}&key={{ config('services.google_maps.key') }}&center={{ $ride->start_lat }},{{ $ride->start_lng }}"
            alt="Route Map"
            class="w-full h-full object-cover rounded-lg">
    </div>
</div>
