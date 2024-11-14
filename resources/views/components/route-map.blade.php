@props(['ride', 'condense' => false])

<div class="{{ $condense ? 'relative w-full mb-4' : 'relative lg:w-1/3 mb-4 lg:mb-0 lg:mr-6' }}">
    <div class="bg-gray-200 dark:bg-gray-700 rounded overflow-hidden w-full h-64 lg:h-full shadow-lg transition-transform duration-300 transform hover:scale-105">
        <img
            src="{{ $ride->mapUrlSigned }}"
            alt="Route Map"
            class="w-full h-full object-cover">
    </div>
</div>
