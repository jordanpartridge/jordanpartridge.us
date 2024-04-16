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

                <div class="flex justify-center items-center">
                    <img src="/img/bike-joy.jpg" alt="logo" class="rounded-full" width="128" height="128">
                </div>
                <div style="background-image:linear-gradient(160deg,#e66735,#e335e2 50%,#73f7f8, #a729ed)"
                     class="inline-block w-auto p-0.5 shadow rounded-full animate-gradient">
                    <p class="w-auto h-full px-3 bg-slate-50 dark:bg-neutral-900 dark:text-white py-1.5 font-medium text-sm tracking-widest uppercase  rounded-full text-slate-800/90 group-hover:text-white/100">
                        Bike Joy</p>
                </div>
                <h1 class="text-3xl font-normal leading-normal text-center text-slate-800 dark:text-white sm:text-4xl lg:text-5xl shadow-sm">
                    Everyone is entitled to bike joy, some don't want it though.
                </h1>
                <p class="w-full max-w-2xl mx-auto mt-8 text-lg dark:text-white/60 text-slate-500">Early this year I
                    decided without much research to purchase a fat tire bicycle.
                    I figured it would be a nice chill fun ride, and I was correct.</p>
                <p class="w-full max w-2xl mx-auto mt-8 text-lg dark:text-white/60 text-slate-500">I have been riding my
                    bike for a few months now and I have to say it is a lot of fun. Here are some perks of riding a fat
                    bike, but on regular streets</p>
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">
                                Benefit 🌟
                            </th>
                            <th scope="col" class="py-3 px-6">
                                Description 📝
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Great Workout 💪
                            </td>
                            <td class="py-4 px-6">
                                Regular biking can significantly improve cardiovascular fitness and physical stamina,
                                which contributes to overall health and stress reduction.
                            </td>
                        </tr>
                        <tr class="bg-gray-50 border-b dark:bg-gray-700 dark:border-gray-600">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Embrace the Heaviness 🏋️‍♂️
                            </td>
                            <td class="py-4 px-6">
                                Handling a heavier bike can mirror the challenges faced in software development,
                                enhancing your ability to navigate and solve complex problems.
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Explore the City 🚴‍♀️
                            </td>
                            <td class="py-4 px-6">
                                Biking allows you to clear your mind and look at problems from new perspectives, often
                                leading to creative solutions once you're back at your desk.
                            </td>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Meet New People 👫
                            </td>
                            <td class="py-4 px-6">
                                Networking with fellow bikers can lead to unexpected solutions and ideas, as discussing
                                common challenges often sparks innovation.
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Stress Relief 🌿
                            </td>
                            <td class="py-4 px-6">
                                Biking is an effective way to manage stress, offering a break from the high-intensity
                                environment of software development and refreshing your mental state.
                            </td>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Fresh Air and Nature 🍃
                            </td>
                            <td class="py-4 px-6">
                                Engaging with nature while biking can improve your mood and mental focus, reducing
                                feelings of burnout and enhancing productivity.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <div class="p-3">
                    <a style="display:inline-block;background-color:#FC5200;color:#fff;padding:5px 10px 5px 30px;font-size:11px;font-family:Helvetica, Arial, sans-serif;white-space:nowrap;text-decoration:none;background-repeat:no-repeat;background-position:10px center;border-radius:3px;background-image:url('https://badges.strava.com/logo-strava-echelon.png')"
                       href='https://strava.com/athletes/2645359' target="_clean">
                        Follow me on
                        <img src='https://badges.strava.com/logo-strava.png' alt='Strava'
                             style='margin-left:2px;vertical-align:text-bottom' height=13 width=51/>
                    </a>
                </div>


                <h5 class="mt-5 text-4xl font-light leading-tight tracking-tight text-center dark:text-white text-slate-800 sm:text-5xl md:text-8xl">
                    Recent Rides</h5>
                <div
                    class="mt-8 flex items-center justify-center space-x-5 bg-white dark:bg-gray-800 p-4 shadow-lg rounded-lg">
                    <iframe class="rounded-md shadow-inner" height='454' width='700' frameborder='0'
                            allowtransparency='true' scrolling='no'
                            src='https://www.strava.com/athletes/2645359/latest-rides/6ca39d65357fcc443b84609f5797366fb5c811cd'></iframe>
                    <iframe class="rounded-md shadow-inner" height='160' width='700' frameborder='0'
                            allowtransparency='true' scrolling='no'
                            src='https://www.strava.com/athletes/2645359/activity-summary/6ca39d65357fcc443b84609f5797366fb5c811cd'></iframe>
                </div>

            </div>

        </div>
        @endvolt
</x-layouts.marketing>
