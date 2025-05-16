@props(['animated' => true])

<div class="relative flex flex-col items-center justify-center w-full min-h-screen overflow-hidden">
    <!-- Canvas for particles effect -->
    <canvas id="particles-canvas" class="absolute inset-0 z-0"></canvas>

    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-500 ease-in-out z-0"></div>

    <!-- SVG pattern overlay with reduced opacity -->
    <x-svg-header class="absolute top-0 left-0 w-full h-auto opacity-10 dark:opacity-5 z-0"></x-svg-header>

    <!-- Main content with z-index to appear above the background -->
    <div class="flex items-center w-full max-w-6xl px-8 pt-8 pb-16 mx-auto text-gray-800 dark:text-white z-10 relative">
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
                    <div class="relative mx-auto w-36 h-36 mb-8 @if ($animated) animate-float @endif">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-primary-400 to-secondary-400 blur-lg opacity-30 dark:opacity-40 @if ($animated) animate-pulse-slow @endif"></div>
                        <x-ui.avatar class="relative w-36 h-36 mx-auto border-4 border-white dark:border-gray-800 rounded-full shadow-xl"/>
                    </div>

                    <!-- Animated Type Effect with enhanced styling -->
                    <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-5 py-1.5 rounded-full text-sm font-medium shadow-lg">
                        <span x-data="{ titles: ['Laravel Artisan', 'Full-Stack Developer', 'TALL Stack Expert', 'Code Architect'], currentIndex: 0 }"
                              x-init="setInterval(() => { currentIndex = (currentIndex + 1) % titles.length }, 3000)"
                              x-text="titles[currentIndex]"
                              class="inline-block min-w-[170px] text-center">
                        </span>
                    </div>
                </div>

                <h1 class="mt-8 text-5xl font-bold mb-4 @if ($animated) typewriter-text @endif">
                    <span class="bg-gradient-to-r from-primary-500 to-secondary-500 text-transparent bg-clip-text">Jordan Partridge</span>
                </h1>

                <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300">
                    Crafting elegant Laravel applications that combine <span class="highlight">performance</span>, <span class="highlight">beauty</span>, and <span class="highlight">reliability</span>.
                </p>

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

                <!-- Theme toggle with sound effects -->
                <div class="mt-8 inline-block">
                    <audio id="dark-mode-sound" class="audio-toggle">
                        <source src="/assets/audio/dark.mp3" type="audio/mpeg">
                    </audio>
                    <audio id="light-mode-sound" class="audio-toggle">
                        <source src="/assets/audio/light.mp3" type="audio/mpeg">
                    </audio>

                    <div class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-md transition-all duration-300">
                        <x-ui.light-dark-switch
                            x-data="{
                                darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                                toggleDarkMode() {
                                    this.darkMode = !this.darkMode;
                                    localStorage.theme = this.darkMode ? 'dark' : 'light';

                                    if (this.darkMode) {
                                        document.documentElement.classList.add('dark');
                                        document.getElementById('dark-mode-sound').play();
                                    } else {
                                        document.documentElement.classList.remove('dark');
                                        document.getElementById('light-mode-sound').play();
                                    }
                                }
                            }"
                        />
                    </div>
                </div>

                <!-- Scroll down indicator -->
                <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex flex-col items-center text-gray-500 dark:text-gray-400 animate-bounce cursor-pointer" onclick="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
                    <span class="text-sm mb-1">Scroll Down</span>
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>