<x-layouts.app
    title="Jordan Partridge | Laravel Developer"
    metaDescription="Laravel expert specializing in building clean, maintainable, and high-performance web applications."
>
    <?php
    use App\Models\Ride;
    use Illuminate\Support\Facades\Cache;

    // Cache the latest ride for 15 minutes to improve homepage performance
    $latestRide = Cache::remember('homepage_latest_ride', 60 * 15, function () {
        return Ride::latest('date')->first();
    });
    ?>
    <div x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
         class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">

        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-b from-primary-400/10 to-transparent dark:from-primary-600/10 -z-10 rounded-bl-[100px]"></div>
        <div class="absolute bottom-0 left-0 w-1/4 h-1/4 bg-gradient-to-t from-primary-400/10 to-transparent dark:from-primary-600/10 -z-10 rounded-tr-[100px]"></div>

        <!-- Animated Dots - Visible only on larger screens -->
        <div class="hidden lg:block absolute top-20 left-20 w-64 h-64 -z-10">
            <div class="absolute w-2 h-2 bg-primary-500/30 rounded-full animate-pulse" style="top: 10%; left: 20%;"></div>
            <div class="absolute w-3 h-3 bg-blue-500/30 rounded-full animate-pulse" style="top: 35%; left: 80%; animation-delay: 1s;"></div>
            <div class="absolute w-2 h-2 bg-teal-500/30 rounded-full animate-pulse" style="top: 70%; left: 40%; animation-delay: 2s;"></div>
        </div>
        <div class="hidden lg:block absolute top-20 right-20 w-64 h-64 -z-10">
            <div class="absolute w-2 h-2 bg-purple-500/30 rounded-full animate-pulse" style="top: 15%; right: 30%; animation-delay: 0.5s;"></div>
            <div class="absolute w-3 h-3 bg-cyan-500/30 rounded-full animate-pulse" style="top: 55%; right: 60%; animation-delay: 1.5s;"></div>
            <div class="absolute w-2 h-2 bg-primary-500/30 rounded-full animate-pulse" style="top: 80%; right: 20%; animation-delay: 2.5s;"></div>
        </div>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 relative">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <!-- Left Column: Avatar and Info -->
                <div class="md:w-1/2 flex flex-col items-center text-center mb-10 md:mb-0">
                    <div class="relative mb-8 md:mb-10">
                        <div class="absolute inset-0 bg-primary-400/20 dark:bg-primary-400/30 rounded-full blur-lg"></div>
                        <x-ui.avatar class="p-0 w-32 h-32 md:w-40 md:h-40" alt="Photo of Jordan Partridge"/>
                        <!-- Moved marquee below the avatar -->
                    </div>

                    <!-- Marquee text below the avatar -->
                    <div class="mb-4 text-lg text-primary-600 dark:text-primary-400 font-medium">
                        <span x-data="{ titles: ['Laravel Expert', 'API Integration Specialist', 'Code Auditor', 'Full-Stack Developer', 'TALL Stack Artisan', 'Fat Bike Enthusiast', 'Package Developer'], currentIndex: 0 }"
                              x-init="setInterval(() => { currentIndex = (currentIndex + 1) % titles.length }, 3000)"
                              x-text="titles[currentIndex]"
                              class="inline-block min-w-[200px]">
                        </span>
                    </div>

                    <!-- Name and Headline -->
                    <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight mb-4">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary-500 to-primary-400 dark:from-primary-400 dark:to-teal-400">
                            Jordan Partridge
                        </span>
                    </h1>

                    <h2 class="text-3xl md:text-4xl font-bold text-gray-700 dark:text-gray-300 mb-6 relative">
                        Laravel Applications That
                        <span class="italic relative inline-block">
                            Simply Work
                            <span class="absolute bottom-1 left-0 w-full h-1 bg-primary-500/30 dark:bg-primary-400/30 rounded-full"></span>
                        </span>
                    </h2>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6">
                        <a href="#expertise" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-md text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-300 hover:shadow-lg hover:scale-105">
                            <span>See My Services</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="/work-with-me" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-primary-500 dark:border-primary-400 text-base font-medium rounded-full shadow-md text-primary-600 dark:text-primary-400 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm hover:bg-primary-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-300 hover:shadow-lg hover:scale-105">
                            <span>Work With Me</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Value Proposition Card -->
                <div class="md:w-1/2 md:pl-10 flex justify-center">
                    <div class="w-full max-w-md bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl shadow-xl p-8 border border-gray-200/50 dark:border-gray-700/50 transform transition-all duration-500 hover:shadow-2xl hover:-rotate-1 hover:scale-[1.02]">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/50 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">Mission-Critical Applications</h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    I build <span class="font-semibold text-primary-600 dark:text-primary-400">Laravel solutions</span> that are clean, maintainable, and delivered on time.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">Performance-Focused</h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    Applications optimized for speed and efficiency for the best user experience.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-teal-100 dark:bg-teal-900/50 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">API Integration</h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    Seamlessly connecting systems with Strava, GitHub, and custom APIs.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
            <!-- Recent Ride Card (if available) -->
            @if (isset($latestRide) && $latestRide)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900/50 p-2 rounded-full mr-3">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">API Integration Example</h2>
                        </div>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-lg">
                                This website demonstrates real-time API integration with Strava to track bike rides. The integration showcases Laravel's powerful API capabilities.
                            </p>
                            <p>
                                The latest ride data is pulled automatically and displayed below. This is an example of the type of integration work I specialize in - connecting systems and displaying data in meaningful ways.
                            </p>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <x-home.recent-ride :ride="$latestRide" />
                    </div>
                </div>
            </div>
            @endif

            <!-- API Integration Packages Section -->
            <div class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto bg-gradient-to-br from-primary-50/50 to-teal-50/50 dark:from-gray-800/50 dark:to-gray-700/50 rounded-3xl mx-4 mb-24">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Open Source API Integration Packages
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Building Laravel packages that make API integrations simple and reliable. These production-ready packages power real applications and are trusted by developers worldwide.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <!-- Strava Client Package -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="bg-orange-100 dark:bg-orange-900/50 p-3 rounded-full mr-4">
                                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.599h4.172L10.463 0l-7 13.828h4.916"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel Strava Client</h3>
                                <p class="text-primary-600 dark:text-primary-400 font-medium">v0.2.0 • Production Ready</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            A comprehensive Laravel package for Strava API integration featuring OAuth authentication, activity tracking, and athlete data management. Built with Laravel best practices and extensive testing.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1 bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 rounded-full text-sm">OAuth 2.0</span>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full text-sm">Rate Limiting</span>
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-full text-sm">Webhook Support</span>
                        </div>
                        <a href="/integrations/strava-client" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- GitHub Client Package -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full mr-4">
                                <svg class="w-8 h-8 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel GitHub Client</h3>
                                <p class="text-primary-600 dark:text-primary-400 font-medium">v0.3.1 • Available Now</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Built on Saloon HTTP client architecture for elegant GitHub API interactions. Features comprehensive repository management, issue tracking, and GitHub Apps integration with robust error handling.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full text-sm">Saloon HTTP</span>
                            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full text-sm">GitHub Apps</span>
                            <span class="px-3 py-1 bg-cyan-100 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-300 rounded-full text-sm">Type Safety</span>
                        </div>
                        <a href="/integrations/github-client" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="/integrations" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        View All Integration Packages
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Services Overview Section -->
            <div class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Laravel Development Services
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        From code audits to performance optimization, I deliver strategic Laravel solutions that give your business a competitive edge.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Code Audit -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Code Audit</h3>
                                <p class="text-blue-600 dark:text-blue-400 font-medium">Security & Quality</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Comprehensive assessment of your codebase to identify vulnerabilities, performance bottlenecks, and optimization opportunities with detailed recommendations.
                        </p>
                        <a href="/services/code-audit" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Performance Optimization -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Performance Optimization</h3>
                                <p class="text-green-600 dark:text-green-400 font-medium">Speed & Efficiency</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Strategic enhancement of application speed and efficiency through database optimization, caching strategies, and streamlined codebase architecture.
                        </p>
                        <a href="/services/performance-optimization" class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Custom Development -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Custom Development</h3>
                                <p class="text-purple-600 dark:text-purple-400 font-medium">Bespoke Solutions</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Tailored Laravel applications built to your exact specifications, from API integrations to complex business logic implementation.
                        </p>
                        <a href="/services/custom-development" class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="/services" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        View All Services
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- About Me Section -->
            <x-home.about-me />

            <!-- Skills Showcase Section -->
            <x-home.skills-showcase />

            <!-- Featured Projects Showcase -->
            <div class="py-24 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
                <h2 class="text-4xl font-bold text-center text-primary-600 dark:text-primary-400 mb-10">
                    Featured GitHub Projects
                </h2>
                <x-software-development.github-repositories :limit="2" :columns="2" />
                <div class="mt-10 text-center">
                    <a href="https://github.com/jordanpartridge" target="_blank" class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        View More on GitHub
                        <svg class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Contact CTA Section -->
            <div class="py-32 relative overflow-hidden">
                <!-- Background elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-blue-500/10 dark:from-primary-500/20 dark:to-blue-500/20"></div>

                <!-- Animated dot patterns -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30 dark:opacity-40 pointer-events-none">
                    <div class="absolute h-4 w-4 rounded-full bg-primary-500/40 animate-float" style="top: 10%; left: 15%; animation-delay: 0s;"></div>
                    <div class="absolute h-3 w-3 rounded-full bg-blue-500/40 animate-float" style="top: 30%; left: 5%; animation-delay: 1s;"></div>
                    <div class="absolute h-2 w-2 rounded-full bg-green-500/40 animate-float" style="top: 60%; left: 25%; animation-delay: 2s;"></div>
                    <div class="absolute h-4 w-4 rounded-full bg-teal-500/40 animate-float" style="top: 20%; left: 80%; animation-delay: 0.5s;"></div>
                    <div class="absolute h-3 w-3 rounded-full bg-purple-500/40 animate-float" style="top: 70%; left: 85%; animation-delay: 1.5s;"></div>
                    <div class="absolute h-2 w-2 rounded-full bg-cyan-500/40 animate-float" style="top: 50%; left: 70%; animation-delay: 2.5s;"></div>
                </div>

                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-12 rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 transform transition-all duration-700 hover:shadow-primary-500/20 dark:hover:shadow-primary-500/30">
                        <div class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-blue-600 dark:from-primary-400 dark:to-blue-400">
                            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">
                                Ready to Build Something Amazing?
                            </h2>
                        </div>

                        <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-12">
                            Whether you need a custom Laravel application, seamless API integrations (Strava, GitHub, or any third-party service), or expert technical consultation, I'm here to turn your ideas into reality.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <a href="/contact" class="group relative px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-medium text-lg rounded-xl shadow-lg overflow-hidden transition-all duration-300">
                                <!-- Animated background shine effect -->
                                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-out"></span>
                                <!-- Text content -->
                                <span class="relative flex items-center">
                                    <span>Get in Touch</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </a>
                            <a href="/work-with-me" class="group relative px-8 py-4 bg-white dark:bg-gray-900 text-primary-600 dark:text-primary-400 border border-primary-500/50 dark:border-primary-400/50 hover:border-primary-500 dark:hover:border-primary-400 font-medium text-lg rounded-xl shadow-md transition-all duration-300">
                                <span class="relative flex items-center">
                                    <span>Learn About My Process</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>