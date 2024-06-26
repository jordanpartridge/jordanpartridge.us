<x-layouts.marketing>
    @volt('home')
    <div class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300" x-cloak>
        <x-svg-header class="absolute top-0 left-0 w-full h-auto opacity-10 dark:opacity-5"></x-svg-header>
        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto text-gray-800 dark:text-white">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-8">
                <x-custom-login-link email='jordan@partridge.rocks' class="absolute top-4 right-4 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-colors duration-200"/>

                <div class="mb-12 text-center">
                    <x-ui.avatar class="mx-auto mb-6 w-40 h-40 border-4 border-blue-500 shadow-lg rounded-full transition-transform duration-300 hover:scale-105"/>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-500 to-teal-400 text-transparent bg-clip-text mb-3">
                        Jordan Partridge
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-4">Full Stack Software Engineer | Army Veteran</p>
                    <div class="flex justify-center space-x-6">
                        <a href="https://www.linkedin.com/in/jordan-partridge-8284897/" class="text-blue-500 hover:text-blue-600 transition-colors duration-200" aria-label="LinkedIn">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a href="http://www.github.com/jordanpartridge" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200" aria-label="GitHub">
                            <i class="fab fa-github fa-2x"></i>
                        </a>
                        <a href="https://www.youtube.com/@JordanCodesLaravel" class="text-red-500 hover:text-red-600 transition-colors duration-200" aria-label="YouTube">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                    </div>
                </div>

                <x-home.about-me/>

                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <x-button-link href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank" icon="fab fa-linkedin" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-200">
                        Connect on LinkedIn
                    </x-button-link>
                    <x-button-link href="http://www.github.com/jordanpartridge" target="_blank" icon="fab fa-github" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-full transition-colors duration-200">
                        Follow on GitHub
                    </x-button-link>
                    <x-button-link href="https://www.youtube.com/@JordanCodesLaravel" target="_blank" icon="fab fa-youtube" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-full transition-colors duration-200">
                        Watch on YouTube
                    </x-button-link>
                </div>

                <x-ui.contact-form class="mt-16 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 transition-colors duration-200"/>

                <div class="flex justify-center mt-12">
                    <a href="#projects" class="animate-bounce text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>

                <div class="space-y-16">
                    <div id="projects" class="scroll-mt-16 space-y-16">
                        <h2 class="text-4xl font-extrabold text-gray-800 dark:text-white mb-12 text-center">Featured Projects</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <!-- My Career Advisor Project -->
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2">
                                <div class="bg-gray-100 dark:bg-gray-200 p-6 flex justify-center items-center">
                                    <img class="h-16 object-contain" src="https://www.mycareeradvisor.com/img/mca-logo-wide-orange.png" alt="My Career Advisor">
                                </div>
                                <div class="p-8">
                                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Featured Project</div>
                                    <a href="https://www.mycareeradvisor.com/" target="_blank" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">My Career Advisor</a>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300">A comprehensive career services platform developed by Goodwill, offering resources and tools for job seekers, students, and veterans.</p>
                                    <div class="mt-4 space-y-2">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Key Contributions:</h4>
                                        <ul class="space-y-2">
                                            <li class="flex items-center">
                                                <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-gray-600 dark:text-gray-300">Led in-house transformation</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-gray-600 dark:text-gray-300">Mentored emerging engineers</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-gray-600 dark:text-gray-300">Built scalable Laravel/Vue.js platform</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-gray-600 dark:text-gray-300">Championed diversity and inclusion</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Website Project -->
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2">
                                <div class="bg-gradient-to-r from-blue-500 to-teal-400 p-6 flex justify-center items-center">
                                    <x-ui.avatar class="mx-auto mb-6 w-40 h-40 border-4 border-blue-500 shadow-lg rounded-full transition-transform duration-300 hover:scale-105"/>

                                    <div>
                                        <h3 class="text-2xl font-bold text-white">JordanPartridge.com</h3>
                                        <p class="text-xl font-bold text-white">Explore my Strava rides and blog insights.</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Personal Portfolio</div>
                                    <a href="/bike-joy" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">Strava Integration</a>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300">Discover my cycling adventures, detailed with Strava's data integration.</p>
                                    <a href="/blog" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">Blog</a>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300">Read my latest blog posts where I share my thoughts, experiences, and projects.</p>
                                    <div class="mt-4 space-y-2">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Technologies Used:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Laravel</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Livewire</span>
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">Tailwind CSS</span>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Alpine.js</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-featured-podcast></x-featured-podcast>
                </div>

                <!-- Tech Stack -->
                <div class="mt-16">
                    <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white mb-8">Tech Stack</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @foreach(['Laravel', 'Livewire', 'Tailwind CSS', 'Alpine.js'] as $tech)
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-2xl">{{ substr($tech, 0, 1) }}</span>
                                </div>
                                <p class="text-gray-800 dark:text-gray-200">{{ $tech }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Testimonials, Blog Posts, and YouTube Videos sections... -->
                <!-- (Keep these sections as they are, just ensure they have proper dark mode classes) -->

            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
