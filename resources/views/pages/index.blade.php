<x-layouts.app
    title="Jordan Partridge | Laravel Developer"
    metaDescription="Laravel expert specializing in building clean, maintainable, and high-performance web applications."
>
    <?php
    use App\Models\Ride;

$latestRide = Ride::latest('date')->first();
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
                <div class="md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left mb-10 md:mb-0">
                    <div class="relative mb-8 md:mb-10">
                        <div class="absolute inset-0 bg-primary-400/20 dark:bg-primary-400/30 rounded-full blur-lg"></div>
                        <x-ui.avatar class="p-0 w-32 h-32 md:w-40 md:h-40" alt="Photo of Jordan Partridge"/>
                        <!-- Moved marquee below the avatar -->
                    </div>

                    <!-- Marquee text below the avatar -->
                    <div class="mb-4 text-lg text-primary-600 dark:text-primary-400 font-medium">
                        <span x-data="{ titles: ['Laravel Expert', 'Full-Stack Developer', 'TALL Stack Artisan'], currentIndex: 0 }"
                              x-init="setInterval(() => { currentIndex = (currentIndex + 1) % titles.length }, 3000)"
                              x-text="titles[currentIndex]"
                              class="inline-block min-w-[180px]">
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
                    <div class="flex flex-col sm:flex-row items-center md:items-start justify-center md:justify-start gap-4 mt-6">
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
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">Robust & Secure</h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    Following best practices for security and reliability.
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
                            Whether you're looking for a custom Laravel application, API integration, or technical consultation, I'm here to turn your ideas into reality.
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