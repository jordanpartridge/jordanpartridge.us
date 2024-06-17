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
        <x-svg-header></x-svg-header>
        <div class="flex items-center w-full max-w-6xl px-8 pt-12 pb-20 mx-auto text-white">
            <div class="container relative max-w-4xl mx-auto mt-20 text-center sm:mt-24 lg:mt-32 space-y-10">
                <x-custom-login-link email='jordan@partridge.rocks'/>

                <div class="flex justify-center items-center p-4">
                    <img src="/img/logo.jpg" alt="logo"
                         class="rounded-full border-4 border-gray-800 p-1 shadow-lg dark:shadow-gray-900 transform transition-transform duration-300 hover:scale-110"
                         width="128" height="128">
                </div>

                <div style="background-image:linear-gradient(160deg,#4d35e6,#3580e3 50%,#73f7f8, #a729ed)"
                     class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                    <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase rounded-full text-slate-800/90 group-hover:text-white/100">
                        Jordan Partridge</p>
                </div>

                <h1 class="mt-5 text-4xl font-light leading-tight tracking-tight text-center dark:text-white text-slate-800 sm:text-5xl md:text-8xl">
                    Passionate Software Engineer</h1>

                <p class="w-full max-w-2xl mx-auto mt-8 text-lg dark:text-white/60 text-slate-200">
                    I am a dedicated and passionate software engineer with expertise in Laravel, Livewire, and Tailwind
                    CSS. My professional journey has been driven by a commitment to building efficient and scalable web
                    applications.
                </p>

                <p class="w-full max-w-2xl mx-auto mt-4 text-lg dark:text-white/60 text-slate-200">
                    Outside of work, I am an avid foodie who loves exploring new cuisines. I also enjoy riding my fat
                    bike, finding adventure on various terrains. This blend of professional dedication and personal
                    passions keeps me motivated and inspired.
                </p>

                <div class="flex items-center justify-center w-full max-w-sm px-5 mx-auto mt-8 space-x-5">
                    <a href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank"
                       class="relative inline-block px-6 py-3 bg-gray-700 text-white rounded-lg overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:bg-gray-800">
                        LinkedIn
                    </a>
                    <a href="https://github.com/jordanpartridge" target="_blank"
                       class="relative inline-block px-6 py-3 bg-blue-600 text-white rounded-lg overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:bg-blue-700">
                        View GitHub
                    </a>
                </div>
            <
                <x-youtube-video url="{{ $podcast_url }}"
                                 title="{{ $podcast_title }}"
                                 description="{{$podcast_description}}"/>

                <!-- Recent Rides Section -->
                <div class="container mx-auto px-4 mt-10">
                    <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">Recent Rides</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach (\App\Models\Ride::latest()->take(6)->get() as $ride)
                            <div
                                class="flex flex-col items-center justify-center w-full p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-xl transform transition-transform duration-300 hover:scale-105">
                                <x-bike-joy.ride :ride="$ride" :condense="true"/>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 flex justify-center">
                        <a href="/bike"
                           class="relative inline-block px-8 py-3 bg-gradient-to-r from-blue-500 to-green-500 text-white rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-110 hover:shadow-2xl hover:from-green-500 hover:to-blue-500">
        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v1a3 3 0 01-6 0v-1a3 3 0 013-3h1.5a2.5 2.5 0 000-5H3m13-4h.01M9 11V9a3 3 0 013-3h1.5a2.5 2.5 0 000-5H15a3 3 0 013 3v1a3 3 0 003 3h-1.5a2.5 2.5 0 000 5H21M9 11h6"/>
            </svg>
        </span>
                            <span>View More Bike Adventures</span>
                        </a>
                    </div>
                </div>

                <!-- Additional Suggested Content Sections -->
                <div class="container mx-auto px-4 mt-10">
                    <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">My Projects</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ([
                            ['title' => 'My Career Advisor', 'description' => 'A no cost career services platform powered by Goodwill', 'url' => 'https://www.mycareeradvisor.com'],
                        ] as $project)
                            <div
                                class="flex flex-col items-center justify-center w-full p-8 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-xl">
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{$project['title']}}</h3>
                                <p class="mt-2 text-base text-gray-800 dark:text-white">{{$project['description']}}</p>
                                <a href="{{$project['url']}}" target="_blank"
                                   class="mt-4 text-blue-600 hover:underline">View Project</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Code and Technologies Section -->
                <div class="container mx-auto px-4 mt-10">
                    <h2 class="text-4xl font-semibold text-slate-800 dark:text-white mb-8 text-center">Code and Technologies</h2>
                    <p class="w-full max-w-3xl mx-auto text-lg dark:text-white/70 text-slate-600 text-center mb-10">
                        This site is built using a modern web development stack to ensure performance, scalability, and ease of maintenance. You can find the code on GitHub and learn more about the technologies used below:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- GitHub Link Card -->
                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <svg class="h-12 w-12 text-gray-900 dark:text-white mb-4" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title>GitHub</title>
                                <path d="M12 0C5.37 0 0 5.373 0 12c0 5.303 3.438 9.8 8.207 11.387.599.111.793-.261.793-.578v-2.234c-3.338.725-4.042-1.416-4.042-1.416-.546-1.387-1.334-1.757-1.334-1.757-1.09-.746.083-.731.083-.731 1.205.084 1.839 1.237 1.839 1.237 1.07 1.835 2.809 1.305 3.495.998.108-.775.419-1.305.762-1.606-2.665-.3-5.466-1.334-5.466-5.93 0-1.31.467-2.381 1.235-3.221-.124-.303-.535-1.521.117-3.168 0 0 1.008-.322 3.3 1.23A11.51 11.51 0 0 1 12 5.803c1.02.004 2.042.137 3.003.402 2.29-1.552 3.297-1.23 3.297-1.23.653 1.647.242 2.865.118 3.168.77.84 1.233 1.911 1.233 3.221 0 4.606-2.804 5.625-5.475 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.193.694.801.577C20.565 21.795 24 17.299 24 12 24 5.373 18.63 0 12 0z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">GitHub Repository</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                Find the complete code for this site on GitHub.
                            </p>
                            <a href="https://github.com/jordanpartridge/your-repo" target="_blank" class="text-blue-600 hover:underline">
                                View Code on GitHub
                            </a>
                        </div>

                        <!-- Laravel Card -->
                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" class="h-12 mb-4">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">Laravel</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                A powerful PHP framework for building robust web applications.
                            </p>
                            <a href="https://laravel.com" target="_blank" class="text-blue-600 hover:underline">
                                Learn more
                            </a>
                        </div>

                        <!-- Livewire Card -->
                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <img src="https://laravel-livewire.com/img/underwater_jelly.svg" alt="Livewire Logo" class="h-12 mb-4">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">Livewire</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                Simplifies the creation of dynamic interfaces without writing JavaScript.
                            </p>
                            <a href="https://laravel-livewire.com" target="_blank" class="text-blue-600 hover:underline">
                                Learn more
                            </a>
                        </div>

                        <!-- Tailwind CSS Card -->
                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Tailwind_CSS_Logo.svg/512px-Tailwind_CSS_Logo.svg.png?20230715030042" alt="Tailwind CSS Logo" class="h-12 mb-4">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">Tailwind CSS</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                A utility-first CSS framework for creating custom designs directly in your markup.
                            </p>
                            <a href="https://tailwindcss.com" target="_blank" class="text-blue-600 hover:underline">
                                Learn more
                            </a>
                        </div>

                        <!-- Volt Card -->
                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <img src="https://laravel-livewire.com/img/underwater_jelly.svg" alt="Volt Logo" class="h-12 mb-4">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">Volt</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                Provides a functional API for Livewire components, promoting cleaner and more concise code.
                            </p>
                            <a href="https://github.com/livewire/volt" target="_blank" class="text-blue-600 hover:underline">
                                Learn more
                            </a>
                        </div>

                        <div class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                            <img src="https://developers.strava.com/images/strava_logo_nav.png" alt="Strava Logo" class="h-12 mb-4">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">Strava Integration</h3>
                            <p class="text-base text-gray-600 dark:text-gray-400 text-center mb-4">
                                This site features a seamless integration with the Strava API to sync bike ride data hourly using Laravel’s scheduler.
                            </p>
                            <a href="https://developers.strava.com" target="_blank" class="text-blue-600 hover:underline">
                                Learn more about Strava API
                            </a>
                        </div>




            </div>

    @endvolt

</x-layouts.marketing>
