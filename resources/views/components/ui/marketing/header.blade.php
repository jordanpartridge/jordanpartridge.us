<header x-data="{
    mobileMenuOpen: false,
    servicesOpen: false,
    integrationsOpen: false,
    softwareOpen: false
}" class="w-full bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50">
    <div class="relative z-20 flex items-center justify-between w-full h-20 max-w-7xl px-6 mx-auto">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center space-x-4 md:space-x-8">
                <a href="{{ route('home') }}" class="flex items-center shrink-0 transition-transform duration-200 hover:scale-105">
                    <x-ui.logo class="block w-auto h-8 text-gray-800 fill-current dark:text-gray-200"/>
                </a>

                <nav class="hidden md:flex space-x-1 md:space-x-6">
                    <x-ui.nav-link href="/" dusk="nav-home">Home</x-ui.nav-link>

                    <!-- Fat Bike Corps Link - Now as a primary navigation item -->
                    <a href="/bike" dusk="nav-bike" class="flex items-center px-3 py-2 text-sm font-medium text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-200 border-b-2 border-transparent hover:border-primary-500 dark:hover:border-primary-400">
                        <i class="fas fa-biking mr-1"></i>
                        <span>Fat Bike Corps</span>
                    </a>

                    <!-- Software Development Dropdown -->
                    <div class="relative" @click.away="softwareOpen = false">
                        <button
                            @click="softwareOpen = !softwareOpen"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none"
                        >
                            <span>Software</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div
                            x-show="softwareOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 w-48 mt-2 origin-top-left bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            x-cloak
                        >
                            <div class="py-1">
                                <a href="/software-development" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Overview</a>
                                <a href="/software-development#tech-stack" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Tech Stack</a>
                                <a href="/software-development#github-projects" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">GitHub Projects</a>
                            </div>
                        </div>
                    </div>

                    <!-- Integrations Dropdown -->
                    <div class="relative" @click.away="integrationsOpen = false">
                        <button
                            @click="integrationsOpen = !integrationsOpen"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none"
                        >
                            <span>Integrations</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div
                            x-show="integrationsOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 w-48 mt-2 origin-top-left bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            x-cloak
                        >
                            <div class="py-1">
                                <a href="/strava-client" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-biking text-primary-500 mr-2"></i>
                                        <span>Strava Client</span>
                                    </div>
                                </a>
                                <a href="/software-development#github-api" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <i class="fab fa-github text-primary-500 mr-2"></i>
                                        <span>GitHub API</span>
                                    </div>
                                </a>
                                <a href="/software-development#gmail-api" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <i class="fab fa-google text-primary-500 mr-2"></i>
                                        <span>Gmail API</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Services Dropdown -->
                    <div class="relative" @click.away="servicesOpen = false">
                        <button
                            @click="servicesOpen = !servicesOpen"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none"
                        >
                            <span>Services</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div
                            x-show="servicesOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 w-56 mt-2 origin-top-left bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            x-cloak
                        >
                            <div class="py-1">
                                <a href="/services" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Overview</a>
                                <a href="/services/custom-development" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Custom Development</a>
                                <a href="/services/code-audit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Code Audit</a>
                                <a href="/services/performance-optimization" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Performance Optimization</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Link -->
                    @if (view()->exists('pages.blog.index'))
                        <x-ui.nav-link href="/blog">Blog</x-ui.nav-link>
                    @endif

                    <!-- Contact Link -->
                    <a href="/contact" dusk="nav-contact" class="px-3 py-2 text-sm font-medium text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-200 border-b-2 border-transparent hover:border-primary-500 dark:hover:border-primary-400">Contact</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <a href="/work-with-me" dusk="nav-work-with-me" class="hidden xl:flex items-center px-3 py-2 font-medium text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-200 border-b-2 border-transparent hover:border-primary-500 dark:hover:border-primary-400">
                    Work With Me
                </a>
                <a href="/work-with-me" dusk="nav-work-with-me-tablet" class="hidden lg:flex xl:hidden items-center px-3 py-2 text-sm font-medium text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-200 border-b-2 border-transparent hover:border-primary-500 dark:hover:border-primary-400">
                    Work With Me
                </a>
                <div class="w-10 h-10 overflow-hidden rounded-full shadow-sm">
                    <x-ui.light-dark-switch></x-ui.light-dark-switch>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="relative flex items-center justify-center w-10 h-10 md:hidden focus:outline-none"
                        aria-label="Toggle mobile menu">
                    <div class="flex flex-col items-center justify-center w-6 h-6">
                        <span :class="{ '-rotate-45 translate-y-1.5' : mobileMenuOpen }"
                              class="block w-full h-0.5 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                        <span :class="{ 'opacity-0' : mobileMenuOpen }"
                              class="block w-full h-0.5 my-1 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                        <span :class="{ 'rotate-45 -translate-y-1.5' : mobileMenuOpen }"
                              class="block w-full h-0.5 bg-gray-600 dark:bg-gray-300 transition duration-300 ease-out rounded-full"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed inset-0 z-40 pt-20 bg-white dark:bg-gray-900 md:hidden overflow-y-auto"
         @click.away="mobileMenuOpen = false"
         x-cloak>
        <nav class="flex flex-col px-6 py-8 space-y-4 text-lg">
            <x-ui.nav-link href="/" dusk="nav-home" class="py-2 border-b border-gray-200 dark:border-gray-800">Home</x-ui.nav-link>

            <!-- Fat Bike Corps Link - Featured prominently -->
            <a href="/bike" dusk="nav-bike-mobile" class="flex items-center py-2 border-b border-gray-200 dark:border-gray-800 text-primary-600 dark:text-primary-400 font-medium">
                <i class="fas fa-biking mr-2 text-primary-500"></i>
                <span>Fat Bike Corps</span>
            </a>

            <!-- Software Development Section -->
            <div x-data="{ open: false }" class="border-b border-gray-200 dark:border-gray-800 py-2">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                    <span class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Software</span>
                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mt-3 pl-4 space-y-2">
                    <a href="/software-development" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Overview</a>
                    <a href="/software-development#tech-stack" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Tech Stack</a>
                    <a href="/software-development#github-projects" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">GitHub Projects</a>
                </div>
            </div>

            <!-- Integrations Section -->
            <div x-data="{ open: false }" class="border-b border-gray-200 dark:border-gray-800 py-2">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                    <span class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Integrations</span>
                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mt-3 pl-4 space-y-2">
                    <a href="/strava-client" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <div class="flex items-center">
                            <i class="fas fa-biking text-primary-500 mr-2"></i>
                            <span>Strava Client</span>
                        </div>
                    </a>
                    <a href="/software-development#github-api" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <div class="flex items-center">
                            <i class="fab fa-github text-primary-500 mr-2"></i>
                            <span>GitHub API</span>
                        </div>
                    </a>
                    <a href="/software-development#gmail-api" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <div class="flex items-center">
                            <i class="fab fa-google text-primary-500 mr-2"></i>
                            <span>Gmail API</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Services Section -->
            <div x-data="{ open: false }" class="border-b border-gray-200 dark:border-gray-800 py-2">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                    <span class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Services</span>
                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mt-3 pl-4 space-y-2">
                    <a href="/services" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Overview</a>
                    <a href="/services/custom-development" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Custom Development</a>
                    <a href="/services/code-audit" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Code Audit</a>
                    <a href="/services/performance-optimization" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Performance Optimization</a>
                </div>
            </div>

            <!-- Blog Link -->
            @if (view()->exists('pages.blog.index'))
                <x-ui.nav-link href="/blog" class="py-2 border-b border-gray-200 dark:border-gray-800">Blog</x-ui.nav-link>
            @endif

            <!-- Contact Link -->
            <a href="/contact" dusk="nav-contact" class="block py-2 border-b border-gray-200 dark:border-gray-800 text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-all duration-200">Contact</a>

            <!-- Work With Me Link -->
            <div class="pt-4">
                <a href="/work-with-me" dusk="nav-work-with-me" class="block w-full py-3 text-center font-medium text-primary-500 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-200 border-b border-primary-500/50 dark:border-primary-400/50 hover:border-primary-500 dark:hover:border-primary-400">
                    Work With Me
                </a>
            </div>
        </nav>
    </div>
</header>