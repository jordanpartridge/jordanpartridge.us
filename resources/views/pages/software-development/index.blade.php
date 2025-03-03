<x-layouts.marketing>
    @volt('software-development')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <x-software-development.header/>

            <!-- About Me -->
            <div class="mb-20 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50/30 to-secondary-50/30 dark:from-primary-900/20 dark:to-secondary-900/20 opacity-10"></div>
                <div class="relative">
                    <h2 class="text-3xl font-semibold text-primary-600 dark:text-primary-400 mb-6">Hey there! 👋</h2>

                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>I'm Jordan, a full-stack developer with a passion for creating <span class="text-primary-600 dark:text-primary-400 font-semibold">fast</span>, <span class="text-secondary-600 dark:text-secondary-400 font-semibold">beautiful</span>, and <span class="text-green-600 dark:text-green-400 font-semibold">reliable</span> web applications using Laravel.</p>

                        <p>After serving in the Army Signal Corps, I turned my problem-solving skills toward tech, where I build modern web apps that look good and work flawlessly.</p>

                        <h3 class="text-xl font-semibold text-primary-600 dark:text-primary-400 mt-6 mb-2">What I'm all about:</h3>
                        <ul class="space-y-2 ml-5 list-disc">
                            <li><span class="font-medium">Clean Code</span>: Writing maintainable, well-tested code that's easy to understand and extend</li>
                            <li><span class="font-medium">Modern Stack</span>: Leveraging Laravel, Livewire, Alpine.js, and Tailwind CSS to build responsive applications</li>
                            <li><span class="font-medium">Performance Focus</span>: Optimizing for speed and efficiency so your users have the best experience</li>
                            <li><span class="font-medium">Continuous Learning</span>: Always exploring new tools and techniques to stay on the cutting edge</li>
                        </ul>

                        <p class="mt-6">Whether you need a custom web app, API integration, or performance optimization, I'm here to transform your ideas into reality.</p>
                    </div>
                </div>
            </div>

            <!-- GitHub Arsenal -->
            <div class="mb-20">
                <x-software-development.github-arsenal/>

                <!-- Field Operations -->
                <div class="mb-20">
                    <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                        Field Operations</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ([
                            ['My Career Advisor', 'No cost career services platform connecting jobseekers with the resources they need to be successful in their career.', 'https://www.mycareeradvisor.com'],
                            ['Strava Integration', 'Wonder how often I ride my bike? Check it out on my personalized bike page that syncs from strava every hour whether it needs to or not.', '/bike'],
                        ] as [$project, $description, $link])
                            <div
                                class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-green-500/50 dark:hover:shadow-pink-500/50 transition duration-300 group">
                                <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200 group-hover:text-green-500 dark:group-hover:text-pink-400 transition duration-300">{{ $project }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $description }}</p>
                                <a href="{{ $link }}"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition duration-300 inline-flex items-center">
                                    Mission Details
                                    <svg
                                        class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>


                <!-- Call to Action -->
                <div class="text-center">
                    <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-6">
                        Ready to Deploy Your Next Mission?</h2>
                    <div class="relative group inline-block">
                        <a href="mailto:jordan@partridge.rocks?subject=Request%20for%20Laravel%20Development%20Assistance&body=Project%20Details:%0A%0AObjective:%0A%0ATimeline:%0A%0AAdditional%20Information:"
                           class="inline-block bg-gradient-to-r from-primary-500 to-secondary-500 dark:from-primary-600 dark:to-secondary-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-primary-500/50 dark:hover:shadow-secondary-500/50 transition duration-300 relative overflow-hidden">
                            <span class="relative z-10">Request Consultation</span>
                            <span
                                class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                        </a>
                        <span
                            class="absolute top-full left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 mt-2 whitespace-nowrap">
                        Initiate Contact Protocol
                    </span>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                @keyframes pulse {
                    0%, 100% {
                        opacity: 1;
                    }
                    50% {
                        opacity: 0.5;
                    }
                }

                .animate-pulse {
                    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }

                @layer utilities {
                    @keyframes blink {
                        0%, 100% { opacity: 1; }
                        50% { opacity: 0; }
                    }
                    .animate-blink {
                        animation: blink 1s step-end infinite;
                    }
                }
            </style>
    @endpush

    @endvolt
</x-layouts.marketing>
