<x-layouts.app
    title="Jordan Partridge | Laravel Developer"
    metaDescription="Laravel expert specializing in building clean, maintainable, and high-performance web applications."
>
    @volt('home')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-primary-400/20 dark:bg-primary-400/30 rounded-full blur-lg"></div>
                    <x-ui.avatar class="relative w-32 h-32 md:w-40 md:h-40 mx-auto border-4 border-white dark:border-gray-800 rounded-full shadow-xl"/>
                    <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-primary-500 text-white px-4 py-1.5 rounded-full text-sm font-medium shadow-lg">
                        <span x-data="{ titles: ['Laravel Expert', 'Full-Stack Developer', 'TALL Stack Artisan'], currentIndex: 0 }"
                              x-init="setInterval(() => { currentIndex = (currentIndex + 1) % titles.length }, 3000)"
                              x-text="titles[currentIndex]"
                              class="inline-block min-w-[180px] text-center">
                        </span>
                    </div>
                </div>

                <!-- Name and Tagline -->
                <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                    <span class="text-primary-500 dark:text-primary-400">Jordan Partridge</span>
                </h1>

                <h2 class="text-2xl md:text-3xl font-bold text-gray-700 dark:text-gray-300 mb-6">
                    Laravel Applications That <span class="italic">Simply Work</span>
                </h2>

                <!-- Value Proposition -->
                <div class="max-w-xl mx-auto mb-10">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <p class="text-xl text-gray-700 dark:text-gray-300 leading-relaxed">
                            I build <span class="font-semibold text-primary-600 dark:text-primary-400">mission-critical Laravel apps</span> that are clean, maintainable, and delivered on time. No excuses.
                        </p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6 mb-16">
                    <a href="#services" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 hover:scale-105">
                        <span>See My Services</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="/work-with-me" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-primary-500 dark:border-primary-400 text-base font-medium rounded-full shadow-sm text-primary-600 dark:text-primary-400 bg-transparent hover:bg-primary-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 hover:scale-105">
                        <span>Work With Me</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
            <!-- About Me Section -->
            <x-home.about-me />

            <!-- Skills Showcase Section -->
            <x-home.skills-showcase />

            <!-- Professional Journey Section -->
            <x-home.professional-journey />

            <!-- Featured Projects Showcase -->
            <div class="py-20 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
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
            <div class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-6">
                        Ready to Build Something Amazing?
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10">
                        Whether you're looking for a custom Laravel application, API integration, or technical consultation, I'm here to turn your ideas into reality.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-8 mt-10">
                        <a href="/contact" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-lg rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center">
                            <span>Get in Touch</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="/work-with-me" class="px-8 py-4 bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 border-2 border-primary-500 dark:border-primary-400 font-medium text-lg rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            Learn About My Services
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-20">
                        <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm uppercase tracking-wider font-medium">Trusted By</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                            <div class="flex justify-center">
                                <img src="{{ asset('img/clients/goodwill.png') }}" alt="Goodwill" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                            </div>
                            <div class="flex justify-center">
                                <img src="{{ asset('img/clients/pstrax.png') }}" alt="PSTrax" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                            </div>
                            <div class="flex justify-center">
                                <img src="{{ asset('img/clients/atmosol.png') }}" alt="Atmosol" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                            </div>
                            <div class="flex justify-center">
                                <img src="{{ asset('img/clients/insight.png') }}" alt="Insight" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.app>