<x-layouts.marketing>
    @volt('home')
    <div
            class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300"
            x-cloak>
        <x-svg-header class="absolute top-0 left-0 w-full h-auto opacity-10 dark:opacity-5"></x-svg-header>
        <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto text-gray-800 dark:text-white">
            <div class="container relative max-w-4xl mx-auto mt-12 text-center space-y-8">
                <x-custom-login-link email='jordan@partridge.rocks'
                                     class="absolute top-4 right-4 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-colors duration-200"/>

                <div class="mb-12 text-center">
                    <x-ui.avatar
                            class="mx-auto mb-6 w-40 h-40 border-4 border-blue-500 shadow-lg rounded-full transition-transform duration-300 hover:scale-105"/>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-500 to-teal-400 text-transparent bg-clip-text mb-3">
                        Jordan Partridge
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-4">Full Stack Software Engineer | Army
                        Veteran</p>
                    <div class="flex justify-center space-x-6">
                        <a href="https://www.linkedin.com/in/jordan-partridge-8284897/"
                           class="text-blue-500 hover:text-blue-600 transition-colors duration-200"
                           aria-label="LinkedIn">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a href="http://www.github.com/jordanpartridge"
                           class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200"
                           aria-label="GitHub">
                            <i class="fab fa-github fa-2x"></i>
                        </a>
                        <a href="https://www.youtube.com/@JordanCodesLaravel"
                           class="text-red-500 hover:text-red-600 transition-colors duration-200" aria-label="YouTube">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                    </div>
                </div>

                <x-home.about-me/>

                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <x-button-link href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank"
                                   icon="fab fa-linkedin"
                                   class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-200">
                        Connect on LinkedIn
                    </x-button-link>
                    <x-button-link href="http://www.github.com/jordanpartridge" target="_blank" icon="fab fa-github"
                                   class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-full transition-colors duration-200">
                        Follow on GitHub
                    </x-button-link>
                    <x-button-link href="https://www.youtube.com/@JordanCodesLaravel" target="_blank"
                                   icon="fab fa-youtube"
                                   class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-full transition-colors duration-200">
                        Watch on YouTube
                    </x-button-link>
                </div>

                <x-ui.contact-form
                        class="mt-16 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 transition-colors duration-200"/>

                <div class="flex justify-center mt-12">
                    <a href="#projects"
                       class="animate-bounce text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>

                <div class="space-y-16">
                    <x-project-showcase :projects="[
    [
        'name' => 'My Career Advisor',
        'category' => 'Featured Project',
        'url' => 'https://www.mycareeradvisor.com/',
        'description' => 'A comprehensive career services platform developed by Goodwill, offering resources and tools for job seekers, students, and veterans.',
        'logo' => 'https://www.mycareeradvisor.com/img/mca-logo-wide-orange.png',
        'headerClass' => 'bg-gray-100 dark:bg-gray-200',
            'headerContent' => [
            'title' => '',
            'subtitle' => '',
        ],
        'contributions' => [
            'Led in-house transformation',
            'Mentored emerging engineers',
            'Built scalable Laravel/Vue.js platform',
            'Championed diversity and inclusion',
        ],
    ],
    [
        'name' => 'JordanPartridge.com',
        'category' => 'Personal Portfolio',
        'url' => '/',
        'description' => 'My personal website showcasing my projects, blog, and Strava integration.',
        'logo' => '/img/logo.jpg',
        'headerClass' => 'bg-gradient-to-r from-blue-500 to-teal-400',
        'headerContent' => [
            'title' => 'JordanPartridge.com',
            'subtitle' => 'Explore my Strava rides and blog insights.',
        ],
        'subProjects' => [
            [
                'name' => 'Strava Integration',
                'url' => '/bike',
                'description' => 'Discover my cycling adventures, detailed with Strava\'s data integration.',
            ],
            [
                'name' => 'Blog',
                'url' => '/blog',
                'description' => 'Read my latest blog posts where I share my thoughts, experiences, and projects.',
            ],
        ],
        'technologies' => [
            ['name' => 'Laravel', 'color' => 'blue'],
            ['name' => 'Livewire', 'color' => 'green'],
            ['name' => 'Tailwind CSS', 'color' => 'indigo'],
            ['name' => 'Alpine.js', 'color' => 'yellow'],
        ],
    ],
]">
                        Featured Projects
                    </x-project-showcase>
                    <x-featured-podcast></x-featured-podcast>
                </div>

                <!-- Tech Stack -->
         <x-software-development.arsenal></x-software-development.arsenal>
                <!-- Testimonials, Blog Posts, and YouTube Videos sections... -->
                <!-- (Keep these sections as they are, just ensure they have proper dark mode classes) -->

            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
