<x-layouts.marketing>
    @volt('home')
    <div class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300" x-cloak>
        <x-svg-header class="absolute top-0 left-0 w-full h-auto opacity-10 dark:opacity-5"></x-svg-header>

        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto text-gray-800 dark:text-white">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-8">

                <!-- Enhanced Hero Section with Modern Design -->
                <div class="mb-16 text-center relative">
                    <!-- Decorative Elements -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary-400/10 dark:bg-primary-600/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-secondary-400/10 dark:bg-secondary-600/10 rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative">
                        <!-- Avatar with animated glow effect -->
                        <div class="relative mx-auto w-36 h-36 mb-8">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-primary-400 to-secondary-400 blur-lg opacity-30 dark:opacity-40 animate-pulse-slow"></div>
                            <x-ui.avatar class="relative w-36 h-36 mx-auto border-4 border-white dark:border-gray-800 rounded-full shadow-xl animate-float"/>
                        </div>

                        <!-- Animated Type Effect with enhanced styling -->
                        <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-5 py-1.5 rounded-full text-sm font-medium shadow-lg">
                            <span x-data="{ titles: ['Engineering Manager', 'Laravel Developer', 'Army Veteran', 'Cycling Enthusiast'], currentIndex: 0 }"
                                  x-init="setInterval(() => { currentIndex = (currentIndex + 1) % titles.length }, 3000)"
                                  x-text="titles[currentIndex]"
                                  class="inline-block min-w-[170px] text-center">
                            </span>
                        </div>
                    </div>

                    <h1 class="mt-8 text-5xl font-bold bg-gradient-to-r from-primary-500 to-secondary-500 text-transparent bg-clip-text mb-4">
                        Jordan Partridge
                    </h1>

                    <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">
                        Building modern web applications with Laravel and a passion for clean code.
                    </p>

                    <!-- Quick Overview Card with enhanced styling -->
                    <div class="mt-12 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-all duration-300 hover:shadow-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <!-- Backend -->
                            <div class="space-y-4">
                                <h3 class="font-semibold text-blue-500 dark:text-blue-400 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                    </svg>
                                    Backend Development
                                </h3>
                                <div class="space-y-3">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Core Framework</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Laravel</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">PHP 8.2</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Composer</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Authentication & Security</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Sanctum</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Gates</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Policies</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Debugging & Monitoring</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Telescope</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Horizon</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Debug Bar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Frontend -->
                            <div class="space-y-4">
                                <h3 class="font-semibold text-teal-500 dark:text-teal-400 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Frontend Stack
                                </h3>
                                <div class="space-y-3">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Core Technologies</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Livewire</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Alpine.js</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Tailwind</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Build Tools</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Vite</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">PostCSS</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">npm</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">UI Components</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Blade Components</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">HeadlessUI</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Forms</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quality & Testing -->
                            <div class="space-y-4">
                                <h3 class="font-semibold text-purple-500 dark:text-purple-400 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Quality & Testing
                                </h3>
                                <div class="space-y-3">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Testing Frameworks</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Pest</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">PHPUnit</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">TDD</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Code Quality</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Pint</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">PHPStan</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Duster</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">CI/CD</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">GitHub Actions</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Docker</span>
                                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded-full">Forge</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Additional Development Tools -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Additional Tools & Practices</h3>
                            <div class="flex flex-wrap gap-3">
                                <span class="px-4 py-2 text-sm bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full">Event Sourcing</span>
                                <span class="px-4 py-2 text-sm bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full">Service Container</span>
                                <span class="px-4 py-2 text-sm bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full">Package Development</span>
                                <span class="px-4 py-2 text-sm bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">API Development</span>
                                <span class="px-4 py-2 text-sm bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 rounded-full">Queue Management</span>
                                <span class="px-4 py-2 text-sm bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full">Webhooks</span>
                            </div>
                        </div>
                    </div>
                    <!-- Technical Mastery Section -->
                    <div class="space-y-12 mt-16">
                        <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-blue-500 to-teal-400 text-transparent bg-clip-text">Technical Expertise</h2>

                        <!-- Architecture & Development -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Event-Driven Systems
                                </h3>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Event Sourcing Architecture
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Event Broadcasting & Webhooks
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Domain Events & Listeners
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Projections & Read Models
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Error Detection & Monitoring
                                </h3>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Sentry Integration & Setup
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Telescope Debugging
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Exception Handling & Recovery
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Real-time Error Notifications
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Advanced Logging
                                </h3>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Slack Integration for Alerts
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Log Aggregation & Analysis
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Custom Log Channels
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Performance Monitoring
                                </h3>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                        New Relic APM Integration
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                        Query Performance Analysis
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                        Memory Leak Detection
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                        System Health Metrics
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Social Links with Floating Animation -->
                    <div class="mt-10 flex justify-center space-x-8">
                        <a href="https://www.linkedin.com/in/jordan-partridge-8284897/"
                           class="group relative"
                           aria-label="LinkedIn">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-full shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <i class="fab fa-linkedin fa-2x text-primary-500 group-hover:text-primary-600 transition-colors duration-200"></i>
                            </div>
                            <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 px-3 py-1.5 text-xs font-medium bg-gray-900 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">Connect</span>
                        </a>
                        <a href="http://www.github.com/jordanpartridge"
                           class="group relative"
                           aria-label="GitHub">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-full shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <i class="fab fa-github fa-2x text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors duration-200"></i>
                            </div>
                            <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 px-3 py-1.5 text-xs font-medium bg-gray-900 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">Follow</span>
                        </a>
                        <a href="https://www.youtube.com/@JordanCodesLaravel"
                           class="group relative"
                           aria-label="YouTube">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-full shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <i class="fab fa-youtube fa-2x text-red-500 group-hover:text-red-600 transition-colors duration-200"></i>
                            </div>
                            <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 px-3 py-1.5 text-xs font-medium bg-gray-900 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">Subscribe</span>
                        </a>
                    </div>
                </div>

                <!-- Rest of your existing content -->
                <x-home.about-me/>

                <!-- Enhanced Call-to-Action Buttons with Modern Design -->
                <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-4 mt-12">
                    <x-button-link href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank"
                                  icon="fab fa-linkedin"
                                  class="group px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-full shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-medium">
                        <div class="flex items-center justify-center">
                            <i class="fab fa-linkedin text-xl mr-3 group-hover:animate-pulse"></i>
                            <span>Connect on LinkedIn</span>
                        </div>
                    </x-button-link>

                    <x-button-link href="http://www.github.com/jordanpartridge" target="_blank"
                                  icon="fab fa-github"
                                  class="group px-8 py-3.5 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white rounded-full shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-medium">
                        <div class="flex items-center justify-center">
                            <i class="fab fa-github text-xl mr-3 group-hover:animate-pulse"></i>
                            <span>Follow on GitHub</span>
                        </div>
                    </x-button-link>

                    <x-button-link href="https://www.youtube.com/@JordanCodesLaravel" target="_blank"
                                  icon="fab fa-youtube"
                                  class="group px-8 py-3.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-full shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-medium">
                        <div class="flex items-center justify-center">
                            <i class="fab fa-youtube text-xl mr-3 group-hover:animate-pulse"></i>
                            <span>Watch on YouTube</span>
                        </div>
                    </x-button-link>
                </div>

                <!-- Keep your existing components -->
                <x-ui.contact-form class="mt-16 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 transition-colors duration-200"/>

                <!-- Rest of your existing content... -->

            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
