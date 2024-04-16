<?php

use function Laravel\Folio\{middleware, name};
use function Livewire\Volt\{state, rules};

name('bike');
middleware(['redirect-to-dashboard']);

?>

<x-layouts.marketing>
    @volt('bike')
    <div class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden" x-cloak>

        <svg
            class="absolute top-0 left-0 w-7/12 -ml-40 -translate-x-1/2 fill-current opacity-10 dark:opacity-5 text-slate-400"
            viewBox="0 0 978 615" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M978 216.141C656.885 277.452 321.116 341.682 0 402.993c39.425-4.071 128.449-11.563 167.843-15.912l6.661 22.46c59.138 174.752 275.144 254.906 438.792 172.235 48.902-72.088 119.911-180.018 171.073-255.946L978 216.141ZM611.485 405.155c-19.059 27.934-46.278 66.955-65.782 94.576-98.453 40.793-230.472-11.793-268.175-111.202-1.096-2.89-1.702-5.965-3.379-11.972l382.99-38.6c-16.875 24.845-31.224 46.049-45.654 67.198Z"/>
            <path
                d="m262.704 306.481 1.336-28.817c.25-1.784.572-3.562.951-5.323 17.455-81.121 65.161-136.563 144.708-159.63 81.813-23.725 157.283-5.079 211.302 61.02 6.466 7.912 23.695 33.305 23.695 33.305s107.788-20.295 102.487-22.242C710.939 81.362 569.507-31.34 398.149 8.04 221.871 48.55 144.282 217.1 160.797 331.317c23.221-5.568 78.863-19.192 101.907-24.836Z"/>
            <path
                d="M890.991 458.296c-57.168 2.205-69.605 14.641-71.809 71.809-2.205-57.168-14.641-69.604-71.809-71.809 57.168-2.204 69.604-14.641 71.809-71.809 2.204 57.169 14.641 69.605 71.809 71.809Z"/>
            <path
                d="M890.991 458.296c-57.168 2.205-69.605 14.641-71.809 71.809-2.205-57.168-14.641-69.604-71.809-71.809 57.168-2.204 69.604-14.641 71.809-71.809 2.204 57.169 14.641 69.605 71.809 71.809Z"/>
            <path
                d="M952.832 409.766c-21.048.812-25.626 5.39-26.438 26.438-.811-21.048-5.39-25.626-26.437-26.438 21.047-.811 25.626-5.39 26.437-26.437.812 21.047 5.39 25.626 26.438 26.437Z"/>
        </svg>
        <svg
            class="absolute top-0 right-0 w-7/12 -mr-40 translate-x-1/2 fill-current opacity-10 dark:opacity-5 text-slate-400"
            viewBox="0 0 978 615" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M978 216.141C656.885 277.452 321.116 341.682 0 402.993c39.425-4.071 128.449-11.563 167.843-15.912l6.661 22.46c59.138 174.752 275.144 254.906 438.792 172.235 48.902-72.088 119.911-180.018 171.073-255.946L978 216.141ZM611.485 405.155c-19.059 27.934-46.278 66.955-65.782 94.576-98.453 40.793-230.472-11.793-268.175-111.202-1.096-2.89-1.702-5.965-3.379-11.972l382.99-38.6c-16.875 24.845-31.224 46.049-45.654 67.198Z"/>
            <path
                d="m262.704 306.481 1.336-28.817c.25-1.784.572-3.562.951-5.323 17.455-81.121 65.161-136.563 144.708-159.63 81.813-23.725 157.283-5.079 211.302 61.02 6.466 7.912 23.695 33.305 23.695 33.305s107.788-20.295 102.487-22.242C710.939 81.362 569.507-31.34 398.149 8.04 221.871 48.55 144.282 217.1 160.797 331.317c23.221-5.568 78.863-19.192 101.907-24.836Z"/>
            <path
                d="M890.991 458.296c-57.168 2.205-69.605 14.641-71.809 71.809-2.205-57.168-14.641-69.604-71.809-71.809 57.168-2.204 69.604-14.641 71.809-71.809 2.204 57.169 14.641 69.605 71.809 71.809Z"/>
            <path
                d="M890.991 458.296c-57.168 2.205-69.605 14.641-71.809 71.809-2.205-57.168-14.641-69.604-71.809-71.809 57.168-2.204 69.604-14.641 71.809-71.809 2.204 57.169 14.641 69.605 71.809 71.809Z"/>
            <path
                d="M952.832 409.766c-21.048.812-25.626 5.39-26.438 26.438-.811-21.048-5.39-25.626-26.437-26.438 21.047-.811 25.626-5.39 26.437-26.437.812 21.047 5.39 25.626 26.438 26.437Z"/>
        </svg>

        <div class="flex items-center w-full max-w-6xl px-8 pt-12 pb-20 mx-auto">
            <div class="container relative max-w-4xl mx-auto mt-20 text-center sm:mt-24 lg:mt-32">

                <div class="flex justify-center items-center p-5">
                    <img src="/img/bike-joy.jpg" alt="logo" class="rounded-full" width="128" height="128">
                </div>
                <div class="m-2 p-2">
                <div style="background-image:linear-gradient(160deg,#e66735,#e335e2 50%,#73f7f8, #a729ed)"
                     class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                    <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase  rounded-full text-slate-800/90 group-hover:text-white/100">
                        Bike Joy</p>
                </div>
                </div>
                <h1 class="text-3xl font-normal leading-normal text-center text-slate-800 dark:text-white sm:text-4xl lg:text-5xl shadow-sm">
                    Everyone is entitled to bike joy, some don't want it though.
                </h1>
                <div class="text-center max-w-4xl mx-auto p-4 mt-8 text-lg text-slate-800 dark:text-white/80">
                    <p>
                        In the early part of this year, I made a spontaneous decision to buy a fat tire bicycle. It seemed like a promising way to unwind from the daily grind and inject some fun into my routine. My expectations were surpassed—riding has not only been enjoyable but also incredibly rejuvenating.
                    </p>
                    <p>
                        Over the past few months, as I've navigated city streets and park paths, I've discovered more than just the joy of cycling. Beyond the physical benefits, biking has proven to be a fantastic mental escape, offering a fresh perspective amid the structured chaos of software development. Whether it's the rhythmic pedaling or the refreshing breezes, each ride delivers a new sense of clarity and creativity that enhances my problem-solving skills back at the desk.
                    </p>
                    <p>
                        For those of us entrenched in the digital realm of coding and debugging, biking serves as a perfect counterbalance. Here are some specific perks of incorporating regular biking into our high-tech lives:
                    </p>
                </div>


                <x-bike-joy.perks />

                <div class="p-3">
                    <a style="display:inline-block;background-color:#FC5200;color:#fff;padding:5px 10px 5px 30px;font-size:11px;font-family:Helvetica, Arial, sans-serif;white-space:nowrap;text-decoration:none;background-repeat:no-repeat;background-position:10px center;border-radius:3px;background-image:url('https://badges.strava.com/logo-strava-echelon.png')"
                       href='https://strava.com/athletes/2645359' target="_clean">
                        Follow me on
                        <img src='https://badges.strava.com/logo-strava.png' alt='Strava'
                             style='margin-left:2px;vertical-align:text-bottom' height=13 width=51/>
                    </a>
                </div>


                <div class="bg-white dark:bg-gray-800">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                            Check Out My Recent Rides!
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <iframe src="https://www.strava.com/athletes/2645359/latest-rides/6ca39d65357fcc443b84609f5797366fb5c811cd" frameborder="0" allowtransparency="true" scrolling="no" class="w-full h-64"></iframe>
                            </div>
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <iframe src="https://www.strava.com/athletes/2645359/activity-summary/6ca39d65357fcc443b84609f5797366fb5c811cd" frameborder="0" allowtransparency="true" scrolling="no" class="w-full h-64"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New code -->
                <div class="bg-white dark:bg-gray-800 mt-8">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                            Listen to My Playlist!
                        </h2>
                        <div class="shadow-lg rounded-lg overflow-hidden">
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/playlist/0MUayKfGRk0kRvsaQBDBWe?utm_source=generator&theme=0" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

        </div>
        @endvolt
</x-layouts.marketing>
