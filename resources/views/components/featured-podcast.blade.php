<?php

use function Livewire\Volt\{state};

state([
    'podcast_url'         => app(\App\Settings\FeaturedPodcastSettings::class)->url,
    'podcast_title'       => app(\App\Settings\FeaturedPodcastSettings::class)->title,
    'podcast_description' => app(\App\Settings\FeaturedPodcastSettings::class)->description,
]);

?>

<div id="youtube" class="flex justify-center mt-8">
    @volt('podcast_url', 'podcast_title', 'podcast_description')
    <x-youtube-video url="{{ $podcast_url }}"
                     title="{{ $podcast_title }}"
                     description="{{ $podcast_description }}"/>
    @endvolt
</div>
