<x-layouts.marketing>
    @volt('software-development')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Header with Badges -->
            <header class="text-center mb-20">
                <div
                    class="inline-block rounded-full p-1 bg-gradient-to-r from-blue-500 to-green-500 dark:from-purple-500 dark:to-pink-500 shadow-lg">
                    <img src="/img/logo.jpg" alt="Logo"
                         class="w-40 h-40 rounded-full border-4 border-white dark:border-gray-800">
                </div>
                <h1 class="mt-8 text-5xl md:text-7xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 leading-tight">
                    Software Engineering
                </h1>
                <p class="mt-4 text-2xl text-gray-600 dark:text-gray-300">
                    Army Veteran | Problem Solver
                </p>
                <!-- Laravel Marksman Badge -->
                <div class="mt-6 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-full shadow-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                    Laravel Marksman
                </div>
                <!-- GitHub Decorations -->
                <div class="mt-6 flex justify-center space-x-4">
                    <div
                        class="bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="gold" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Star Coder
                    </div>
                    <div
                        class="bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="silver" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        Bug Hunter
                    </div>
                    <div
                        class="bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="bronze" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        Code Warrior
                    </div>
                </div>
            </header>

            <!-- Main content (keep as is) -->

            <!-- Favorite Frameworks with Military-style Icons -->
            <div class="mb-20">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                    Arsenal</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                    <div
                        class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 flex flex-col items-center relative">
                        <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" class="w-20 h-20 mb-4">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Laravel</span>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 flex flex-col items-center relative">
                        <img src="https://vuejs.org/images/logo.png" alt="Vue.js" class="w-20 h-20 mb-4">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Vue.js</span>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 flex flex-col items-center relative">
                        <img
                            src="/img/tailwind.png"
                            alt="Tailwind CSS" class="w-20 h-20 mb-4">
                        <span class="text-lg font-semibold text-gry-800 dark:text-gray-200">Tailwind CSS</span>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 flex flex-col items-center relative">
                        <img src="https://raw.githubusercontent.com/livewire/livewire/master/art/logo.svg"
                             alt="Livewire" class="w-20 h-20 mb-4">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Livewire</span>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 flex flex-col items-center relative">
                        <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Folio" class="w-20 h-20 mb-4">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Laravel Folio</span>
                    </div>
                </div>
            </div>

            <!-- Projects Showcase with Mission-style Names -->
            <div class="mb-20">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                    Field Operations</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['My Career Advisor', 'No cost career services platform connecting jobseekers with the resources they need to be successful in their career.', 'https://wwww.mycareeradvisor.com'],
                        ['Strava Integration', 'Wonder how often I ride my bike? Check it out on my personalized bike page that syncs from strava every hour whether it needs to or not.', '/bike'],
                    ] as [$project, $description, $link])
                        <div
                            class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-green-500/50 dark:hover:shadow-pink-500/50 transition duration-300">
                            <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">{{ $project }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $description }}</p>
                            <a href="{{ $link }}"
                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition duration-300">Mission
                                Details →</a>
                        </div>
                    @endforeach
                </div>

            </div>


            <!-- Call to Action -->
            <div class="relative group">
                <a href="mailto:jordan@partridge.rocks?subject=Request%20for%20Code%20Commando%20Assistance&body=Mission%20Details:%0A%0ATarget%20Objective:%0A%0AExpected%20Deployment%20Date:%0A%0AAdditional%20Intel:"
                   class="inline-block bg-gradient-to-r from-blue-500 to-green-500 dark:from-purple-500 dark:to-pink-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 group relative overflow-hidden">
                    <span class="relative z-10">Request Briefing</span>
                    <span
                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                    <span
                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <svg class="h-6 w-6 text-blue-600 dark:text-purple-400" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </span>
                </a>
                <span
                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 mb-2">
        Initiate Contact Protocol
    </span>
            </div>

        </div>
    </div>

    @push('styles')
        <style>
            /* Additional styles for badges and decorations */
            .badge-glow {
                animation: glow 2s infinite alternate;
            }

            @keyframes glow {
                from {
                    box-shadow: 0 0 5px -5px #ff6b6b;
                }
                to {
                    box-shadow: 0 0 20px 5px #ff6b6b;
                }
            }
        </style>
    @endpush

    @endvolt
</x-layouts.marketing>
