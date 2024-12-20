<x-layouts.marketing>
    @volt('software-development')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <x-software-development.header/>

            <!-- Mission Brief -->
            <div
                class="mb-20 bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-50 dark:from-blue-900 dark:to-gray-900 opacity-10 animate-pulse"></div>
                <h2 class="text-3xl font-semibold text-blue-600 dark:text-blue-300 mb-4 relative">Mission Brief</h2>
                <div
                    x-data="{
            text: '',
            fullText: `Veteran of both military and digital battlefields, with over a decade of combat-tested experience in software engineering. My tour of duty spans the full stack, with a specialization in Laravel and cutting-edge web technologies.

Mission Objective: To engineer robust, scalable, and secure software solutions that drive mission-critical business operations. My code is my weapon, efficiency is my strategy, and your success is my endgame.

Core Values:
1. Precision: Every line of code is meticulously crafted for optimal performance.
2. Adaptability: Rapidly adjusting to new tech terrains and mission parameters.
3. Teamwork: Collaborating seamlessly with cross-functional units to achieve objectives.
4. Innovation: Constantly exploring new tactics and technologies to maintain our competitive edge.

Lessons from the Front Lines: My time in the Army Signal Corps ingrained the paramount importance of redundancy. In both military communications and software architecture, I ensure multiple layers of reliability to guarantee uninterrupted operations.

Ready to deploy battle-tested solutions for your next big mission? Let's strategize.`,
            isTyping: true,
            skipAnimation: localStorage.getItem('SkipTypewriter') === 'true'
        }"
                    x-init="
            if (skipAnimation) {
                text = fullText;
                isTyping = false;
            } else {
                let i = 0;
                const intervalId = setInterval(() => {
                    text += fullText[i];
                    i++;
                    if (i === fullText.length) {
                        clearInterval(intervalId);
                        isTyping = false;
                        localStorage.setItem('skipAnimation', 'true');
                    }
                }, 20);
            }
        "
                    class="relative"
                >
                    <p
                        class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line"
                        x-text="text"
                        aria-live="polite"
                    ></p>
                    <span
                        x-show="isTyping"
                        class="inline-block w-2 h-5 ml-1 bg-blue-500 animate-blink"
                    ></span>
                    <button
                        x-show="isTyping"
                        @click="text = fullText; isTyping = false; localStorage.setItem('skipAnimation', 'true');"
                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300"
                    >
                        Skip Animation
                    </button>
                </div>
            </div>

            <!-- Arsenal -->
            <div class="mb-20">
                <x-software-development.arsenal/>
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
                    <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-6">
                        Ready to Deploy Your Next Mission?</h2>
                    <div class="relative group inline-block">
                        <a href="mailto:jordan@partridge.rocks?subject=Request%20for%20Code%20Commando%20Assistance&body=Mission%20Details:%0A%0ATarget%20Objective:%0A%0AExpected%20Deployment%20Date:%0A%0AAdditional%20Intel:"
                           class="inline-block bg-gradient-to-r from-blue-500 to-green-500 dark:from-purple-500 dark:to-pink-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 relative overflow-hidden">
                            <span class="relative z-10">Request Briefing</span>
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
