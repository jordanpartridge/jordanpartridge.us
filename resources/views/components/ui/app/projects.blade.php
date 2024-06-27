<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Personal Website Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2">
        <div class="bg-gradient-to-r from-blue-500 to-teal-400 p-6 flex flex-col justify-center items-center relative">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div id="confetti-container"></div>
            </div>
            <x-ui.avatar class="mx-auto mb-6 w-40 h-40 border-4 border-blue-500 shadow-lg rounded-full transition-transform duration-300 hover:scale-105"/>
            <div class="text-center">
                <h3 class="text-2xl font-bold text-white">JordanPartridge.com</h3>
                <p class="text-xl font-bold text-white">You're already here! 🎉</p>
                <p class="text-lg font-semibold text-white mt-2">Explore my projects, Strava rides, and blog insights.</p>
            </div>
        </div>
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Meta Personal Portfolio</div>
            <a href="/" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">Inception-level Website</a>
            <p class="mt-2 text-gray-600 dark:text-gray-300">You're looking at a website about the website you're currently browsing. Mind-bending, right?</p>

            <div class="mt-6 space-y-4">
                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Featured Sections:</h4>
                <div>
                    <a href="/bike-joy" class="block text-lg leading-tight font-bold text-gray-900 dark:text-white hover:underline">Strava Integration</a>
                    <p class="mt-1 text-gray-600 dark:text-gray-300">Discover my cycling adventures, detailed with Strava's data integration.</p>
                </div>
                <div>
                    <a href="/blog" class="block text-lg leading-tight font-bold text-gray-900 dark:text-white hover:underline">Blog</a>
                    <p class="mt-1 text-gray-600 dark:text-gray-300">Read my latest blog posts where I share my thoughts, experiences, and projects.</p>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Technologies Used:</h4>
                <div class="flex flex-wrap gap-4">
                    <i class="fab fa-laravel text-3xl text-red-500"></i>
                    <i class="fab fa-js-square text-3xl text-yellow-500"></i>
                    <i class="fab fa-css3-alt text-3xl text-blue-500"></i>
                    <i class="fab fa-html5 text-3xl text-orange-500"></i>
                    <i class="fab fa-github text-3xl text-gray-700 dark:text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- My Career Advisor Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2">
        <div class="bg-white p-6 flex justify-center items-center">
            <img class="h-16 object-contain" src="https://www.mycareeradvisor.com/img/mca-logo-wide-orange.png" alt="My Career Advisor">
        </div>
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Featured Project</div>
            <a href="https://www.mycareeradvisor.com/" target="_blank" class="block mt-1 text-2xl leading-tight font-bold text-gray-900 dark:text-white hover:underline">My Career Advisor</a>
            <p class="mt-2 text-gray-600 dark:text-gray-300">A comprehensive career services platform developed by Goodwill, offering resources and tools for job seekers, students, and veterans.</p>

            <div class="mt-6 space-y-4">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Key Contributions:</h4>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-code-branch text-green-500 mr-2 mt-1"></i>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Led in-house transformation</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-users text-green-500 mr-2 mt-1"></i>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Mentored emerging engineers</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-server text-green-500 mr-2 mt-1"></i>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Built scalable Laravel/Vue.js platform</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-universal-access text-green-500 mr-2 mt-1"></i>
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Championed diversity and inclusion</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mt-6 space-y-2">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Technologies Used:</h4>
                <div class="flex flex-wrap gap-4">
                    <i class="fab fa-laravel text-3xl text-red-500"></i>
                    <i class="fab fa-vuejs text-3xl text-green-500"></i>
                    <i class="fab fa-bootstrap text-3xl text-purple-500"></i>
                    <i class="fas fa-database text-3xl text-blue-500"></i>
                    <i class="fab fa-aws text-3xl text-orange-500"></i>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="https://www.mycareeradvisor.com/" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Visit Project
                    <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- YouTube Channel Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition duration-300 hover:shadow-2xl transform hover:-translate-y-2 md:col-span-2">
        <div class="relative h-64 md:h-96">
            <img src="https://img.youtube.com/vi/YOUR_VIDEO_ID/maxresdefault.jpg" alt="YouTube Channel Cover" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <h3 class="text-3xl font-bold text-white">My YouTube Channel</h3>
            </div>
        </div>
        <div class="p-8">
            <p class="mt-2 text-gray-600 dark:text-gray-300">Check out my latest videos on software development, career advice, and tech insights.</p>
            <div class="mt-6 flex justify-end">
                <a href="https://www.youtube.com/c/YOUR_CHANNEL" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Visit Channel
                    <i class="fab fa-youtube ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    function launchConfetti() {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }

    document.querySelector('.bg-gradient-to-r').addEventListener('click', launchConfetti);
</script>
