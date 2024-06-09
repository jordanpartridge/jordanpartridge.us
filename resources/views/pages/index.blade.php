<?php

use function Laravel\Folio\{name};

name('home');

?>

<x-layouts.marketing>

    @volt('home')
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
                @env('local')
                    <div class="space-y-2">
                        <x-login-link class="text-gray-700 dark:text-gray-300" email="jordan@partridge.rocks"
                                      label="Login"/>
                    </div>
                @endenv
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

                <div
                    class="relative flex flex-col items-center justify-center w-full h-auto overflow-hidden m-6"
                    x-cloak>
                    <!-- Centralized YouTube Video Section -->
                    <div class="flex flex-col items-center justify-center w-full px-8 pt-12 pb-20">
                        <div class="container max-w-4xl mx-auto text-center">

                            <h2 class="text-3xl font-semibold text-slate-800 dark:text-white mb-4">Podcast Highlight
                            </h2>

                            <div class="max-w-sm mx-auto mt-10">
                                <div class="dark:bg-slate bg-white shadow-md rounded-lg overflow-hidden">
                                    <div class="relative pb-56">
                                        <iframe class="absolute inset-0 w-full h-full"
                                                src="https://www.youtube.com/embed/iT5j2fsemWc" frameborder="0"
                                                allowfullscreen></iframe>
                                    </div>
                                    <div class="p-4 dark:text-white dark:bg-slate-900 bg-slate-100 rounded-b-lg">
                                        <h2 class="text-xl font-semibold">Doing The Work</h2>
                                        <p class="dark:text-sky-100 text-gray-600 mt-2">
                                            Listen as Scott Foley explains the value of doing the work and working on your goals every day.
                                        </p>
                                        <div class="m-6">
                                            <x-youtube-subscribe-button channelId="UC8K4ImiCmXKQO6Lb5A8QMcg"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endvolt

</x-layouts.marketing>
