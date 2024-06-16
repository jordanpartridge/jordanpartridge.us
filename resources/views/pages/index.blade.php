<?php

use function Laravel\Folio\{name};
use function Livewire\Volt\{state};

name('home');

state([
    'podcast_url'         => app(\App\Settings\FeaturedPodcastSettings::class)->url,
    'podcast_title'       => app(\App\Settings\FeaturedPodcastSettings::class)->title,
    'podcast_description' => app(\App\Settings\FeaturedPodcastSettings::class)->description,
]);

?>

<x-layouts.marketing>

    @volt('home')
    <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden" x-cloak>
       <x-svg-header> </x-svg-header>
        <div class="flex items-center w-full max-w-6xl px-8 pt-12 pb-20 mx-auto">
            <div class="container relative max-w-4xl mx-auto mt-20 text-center sm:mt-24 lg:mt-32">
                <x-custom-login-link/>
                <x-ui.image-rounded src="/img/logo.jpg" alt="logo"/>

                <div style="background-image:linear-gradient(160deg,#4d35e6,#3580e3 50%,#73f7f8, #a729ed)"
                     class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                    <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase  rounded-full text-slate-800/90 group-hover:text-white/100">
                        Jordan Partridge</p>
                </div>
                <h1 class="mt-5 text-4xl font-light leading-tight tracking-tight text-center dark:text-white text-slate-800 sm:text-5xl md:text-8xl">
                    Passionate Software Engineer</h1>
                <p class="w-full max-w-2xl mx-auto mt-8 text-lg dark:text-white/60 text-slate-500">Software Engineer |
                    Foodie | Fat bike Fattie</p>
                <div class="flex items-center justify-center w-full max-w-sm px-5 mx-auto mt-8 space-x-5">
                    <x-ui.button type="secondary" tag="a" href="https://www.linkedin.com/in/jordan-partridge-8284897/"
                                 target="_blank">Linkedin
                    </x-ui.button>
                    <x-ui.button type="primary" tag="a" href="https://github.com/jordanpartridge" target="_blank">View
                        Github
                    </x-ui.button>
                </div>

                <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden m-6"
                        x-cloak>
                    <div class="flex flex-col items-center justify-center w-full px-8 pt-12 pb-20">
                      <x-youtube-video url="{{$podcast_url}}"
                                       title="{{$podcast_title}}"
                                       description="{{$podcast_description}}"/>
                </div>
            </div>
        </div>
    </div>
    @endvolt

</x-layouts.marketing>
