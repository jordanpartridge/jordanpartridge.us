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
    <div class="relative parallax flex flex-col items-center justify-center w-full h-auto overflow-hidden" x-cloak>
        <x-svg-header></x-svg-header>
        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto text-white">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-6">
                <x-custom-login-link email='jordan@partridge.rocks'/>
                <x-ui.avatar class="mx-auto my-4"/>
                <div class="relative inline-block w-auto p-0.5 shadow-lg rounded-full overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-600 via-blue-500 to-green-400 opacity-75 transition-opacity duration-500 ease-in-out group-hover:opacity-0"></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-red-500 via-yellow-500 to-pink-500 opacity-0 transition-opacity duration-500 ease-in-out group-hover:opacity-75"></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-600 via-blue-500 to-green-400 opacity-50 blur-md transition-all duration-500 ease-in-out group-hover:opacity-0"></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-red-500 via-yellow-500 to-pink-500 opacity-0 blur-md transition-all duration-500 ease-in-out group-hover:opacity-50"></div>
                    <p class="relative z-10 w-auto h-full px-4 py-1.5 bg-slate-50 dark:bg-neutral-900 dark:text-white font-medium text-sm tracking-widest uppercase rounded-full text-slate-800/90 group-hover:text-white transition-colors duration-300">
                        Jordan Partridge
                    </p>
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-purple-600 via-blue-500 to-green-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out">
                        <svg class="h-6 w-6 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v4m0 8v4m8-8h-4m-8 0H4m14.5-7.5l-3 3m0 10.5l-3 3m-4.5-13.5l-3 3m0 10.5l-3 3"/>
                        </svg>
                    </div>
                </div>

                <h1 class="mt-5 text-3xl font-light leading-tight tracking-tight text-center dark:text-white text-slate-800 sm:text-4xl md:text-5xl">
                    Passionate Software Engineer</h1>

                <p class="w-full max-w-2xl mx-auto mt-6 text-md dark:text-white/60 text-slate-900">
                    I am a dedicated and passionate software engineer with expertise in Laravel, Livewire, and Tailwind
                    CSS. My professional journey has been driven by a commitment to building efficient and scalable web
                    applications.
                </p>

                <p class="w-full max-w-2xl mx-auto mt-4 text-md dark:text-white/60 text-slate-900">
                    Outside of work, I am an avid foodie who loves exploring new cuisines. I also enjoy riding my fat
                    bike, finding adventure on various terrains. This blend of professional dedication and personal
                    passions keeps me motivated and inspired.
                </p>

                <div class="flex items-center justify-center w-full max-w-sm px-5 mx-auto mt-8 space-x-4">
                    <a href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank"
                       class="relative inline-block px-5 py-2 bg-gray-700 text-white rounded-lg overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:bg-gray-800">
                        LinkedIn
                    </a>
                    <a href="https://github.com/jordanpartridge" target="_blank"
                       class="relative inline-block px-5 py-2 bg-blue-600 text-white rounded-lg overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:bg-blue-700">
                        View GitHub
                    </a>
                </div>
                <div class="flex justify-center mt-8">
                    <a href="#youtube" class="animate-bounce">
                        <svg class="h-8 w-8 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>
                <div id="youtube" class="flex justify-center mt-8">

                    <x-youtube-video url="{{ $podcast_url }}"
                                     title="{{ $podcast_title }}"
                                     description="{{$podcast_description}}"/>
                </div>
                <x-bike-joy.recent-rides/>

                <!-- Additional Suggested Content Sections -->
                <x-ui.app.projects/>
                <x-ui.app.built-with/>
            </div>
        </div>
    </div>
    @endvolt

</x-layouts.marketing>

<style>
    .parallax {
        background-attachment: fixed;
        background-image: url('/images/hero.jpg');
        min-height: 400px;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    html {
        scroll-behavior: smooth;
    }

    .animate-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(-25%);
            animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
        }
        50% {
            transform: translateY(0);
            animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }
    }
</style>
