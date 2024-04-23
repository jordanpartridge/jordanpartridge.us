<?php

use function Laravel\Folio\{middleware, name};
use function Livewire\Volt\{state, rules};

name('bike');
middleware(['redirect-to-dashboard']);

\Livewire\Volt\mount(function () {
    $this->startOfWeek = \Carbon\Carbon::now()->startOfWeek();
    $this->endOfWeek   = \Carbon\Carbon::now()->endOfWeek();

    $this->rides              = \App\Models\Ride::whereBetween('date', [$this->startOfWeek, $this->endOfWeek]);
    $this->weeklyMileage      = number_format($this->rides->sum('distance') * 0.000621371, 1);
    $this->weeklyAverageSpeed = $this->rides->avg('average_speed') * 2.23694;
    $this->weeklyMaxSpeed     = $this->rides->max('max_speed') * 2.23694;
    $hours                    = floor($this->rides->sum('moving_time') / 3600);
    $minutes                  = ($this->rides->sum('moving_time') / 60) % 60;
    $this->time               = sprintf("%d hours %d minutes", $hours, $minutes);
    $this->rides              = $this->rides->latest()->limit(6)->get();
    $hours                    = floor($this->rides->sum('elapsed_time') / 3600);
    $minutes                  = ($this->rides->sum('elapsed_time') / 60) % 60;
    $this->elapsedTime        = sprintf("%d hours %d minutes", $hours, $minutes);
    $this->elevation          = number_format($this->rides->sum('elevation') * 3.28084);
}
);

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
                    <x-ui.image-rounded src="/img/bike-joy.jpg"/>
                </div>
                <div class="m-2 p-2">
                    <div
                        style="background-image:linear-gradient(160deg,rgba(20,208,136,0.43),#3566e3 50%,#73f4f8, #110e0f)"
                        class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                        <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase  rounded-full text-slate-800/90 group-hover:text-white/100">
                            Bike Joy</p>
                    </div>
                </div>

                <h1 class="text-3xl font-normal leading-normal text-center text-slate-800 dark:text-white sm:text-4xl lg:text-5xl shadow-sm">
                    Everyone is entitled to bike joy.
                </h1>
                <div id="dashboard" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center">
                        Weekly Statistics ({{ $this->startOfWeek->format('M d') }}
                        - {{ $this->endOfWeek->format('M d') }})
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"> Distance</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{ number_format($this->weeklyMileage, 1) }}
                                miles</p>
                        </div>
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Average Speed Per
                                Ride
                                Speed</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{ number_format($this->weeklyAverageSpeed, 1) }}
                                mph</p>
                        </div>
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Average Max Speed
                                Per
                                Ride</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{ number_format($this->weeklyMaxSpeed, 1) }}
                                mph</p>
                        </div>
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Moving Time</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{ $this->time }}</p>
                        </div>
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Elapsed Time</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{$this->elapsedTime}}</p>
                        </div>
                        <div class="item bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Elevation</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">{{ $this->elevation }} Feet</p>
                        </div>
                    </div>
                    <div class="text-center max-w-4xl mx-auto p-4 mt-8 text-lg text-slate-800 dark:text-white/80">
                        <p>
                            In the early part of this year, I made a spontaneous decision to buy a fat tire bicycle. It
                            seemed like a promising way to unwind from the daily grind and inject some fun into my
                            routine.
                            My expectations were surpassed—riding has not only been enjoyable but also incredibly
                            rejuvenating.
                        </p>
                        <p>
                            Over the past few months, as I've navigated city streets and park paths, I've discovered
                            more
                            than just the joy of cycling. Beyond the physical benefits, biking has proven to be a
                            fantastic
                            mental escape, offering a fresh perspective amid the structured chaos of software
                            development.
                            Whether it's the rhythmic pedaling or the refreshing breezes, each ride delivers a new sense
                            of
                            clarity and creativity that enhances my problem-solving skills back at the desk.
                        </p>
                        <p>
                            For those of us entrenched in the digital realm of coding and debugging, biking serves as a
                            perfect counterbalance. Here are some specific perks of incorporating regular biking into
                            our
                            high-tech lives:
                        </p>
                    </div>


                    <x-bike-joy.perks/>

                    <div class="bg-white dark:bg-gray-800">
                        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                                Check Out My Recent Rides!
                            </h2>
                            @foreach($this->rides as $ride)
                                <div class="w-full m-4 mb-0">
                                    <x-bike-joy.ride :ride="$ride"/>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 mt-8">
                        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                                Listen to My Playlist!
                            </h2>
                            <div class="shadow-lg rounded-lg overflow-hidden">
                                <iframe style="border-radius:12px"
                                        src="https://open.spotify.com/embed/playlist/0MUayKfGRk0kRvsaQBDBWe?utm_source=generator&theme=0"
                                        width="100%" height="352" frameBorder="0" allowfullscreen=""
                                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                        loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endvolt
</x-layouts.marketing>
