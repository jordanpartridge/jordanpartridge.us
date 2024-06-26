<?php

use function Livewire\Volt\state;

state([
    'podcast_url'         => app(\App\Settings\FeaturedPodcastSettings::class)->url,
    'podcast_title'       => app(\App\Settings\FeaturedPodcastSettings::class)->title,
    'podcast_description' => app(\App\Settings\FeaturedPodcastSettings::class)->description,
]);

?>

<div>
    @volt('featured-podcast')

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 flex justify-center items-center">
            <i class="fas fa-podcast text-white text-4xl mr-4"></i>
            <h3 class="text-2xl font-bold text-white">Featured Podcast</h3>
        </div>
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Watch Scott Foley's The Underdog Voice</div>
            <a href="{{ $podcast_url }}" target="_blank" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">
                {{ $podcast_title }}
            </a>
            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $podcast_description }}</p>
            <div class="mt-6">
                <x-youtube-video
                    url="{{ $podcast_url }}"
                    title="{{ $podcast_title }}"
                    description="{{ $podcast_description }}"
                />
            </div>
            <div class="mt-6">
                <a href="{{ $podcast_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Watch Full Episode
                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

    </div>
    @endvolt
</div>
