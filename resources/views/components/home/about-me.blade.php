<div x-data="{ expanded: false, darkMode: false }"
     x-init="darkMode = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)"
     :class="{ 'dark': darkMode }"
     class="mt-8 space-y-6 transition-colors duration-300 ease-in-out">
    <h2 class="text-4xl font-extrabold text-center bg-gradient-to-r from-blue-600 to-teal-400 text-transparent bg-clip-text transition-all duration-300">
        Bridging Technology & Innovation
    </h2>

    <div class="text-lg text-gray-700 dark:text-gray-200 leading-relaxed transition-colors duration-300">
        <p>From writing <span class="text-green-600 dark:text-green-400 font-semibold">BASIC on MS-DOS</span> as a curious kid to architecting <span class="text-blue-600 dark:text-blue-400 font-semibold">enterprise-level applications</span> today, my journey in technology has been driven by an unwavering passion for solving complex problems. As <span class="text-pink-600 dark:text-pink-400 font-bold text-xl">Jordan Partridge</span>, I blend my background in <span class="text-purple-600 dark:text-purple-400 font-semibold">systems engineering</span> with modern software development to create robust, scalable solutions.</p>

        <div x-show="expanded" x-collapse>
            <p class="mt-4">My approach to software development is shaped by a unique combination of experiences. As an <span class="text-orange-600 dark:text-orange-400 font-semibold">engineering manager</span>, I've learned that the best solutions come from understanding both the technical architecture and the human elements. Whether it's designing <span class="text-cyan-600 dark:text-cyan-400 font-semibold">event-driven systems</span> or implementing <span class="text-red-600 dark:text-red-400 font-semibold">fault-tolerant architectures</span>, I believe in building systems that are not just functional, but <span class="text-green-600 dark:text-green-300 font-semibold">maintainable and scalable</span>.</p>

            <p class="mt-4">Today, I focus on pushing the boundaries of what's possible with modern web technologies. As a <span class="text-yellow-600 dark:text-yellow-400 font-semibold">technical architect</span>, I specialize in developing <span class="text-blue-600 dark:text-blue-300 font-semibold">high-performance applications</span> using Laravel and the TALL stack. My passion extends to <span class="text-pink-600 dark:text-pink-300 font-semibold">mentoring developers</span> and contributing to the tech community through open-source projects and knowledge sharing. When I'm not coding, you'll find me exploring new cycling routes or <span class="text-purple-600 dark:text-purple-300 font-semibold">experimenting with emerging technologies</span>.</p>

            <p class="mt-4">I bring a structured yet innovative approach to every project, combining <span class="text-teal-600 dark:text-teal-300 font-semibold">technical expertise</span> with a keen understanding of business needs. My experience spans from building career development platforms to implementing complex event-sourcing systems, always with a focus on creating solutions that make a real impact.</p>
        </div>
    </div>

    <button
        @click="expanded = !expanded"
        class="mt-6 px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 text-white rounded-full hover:from-blue-600 hover:to-teal-500 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 shadow-lg"
        x-text="expanded ? 'Read Less' : 'Read More'"
        aria-expanded="expanded"
    ></button>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="/software-development"
           class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-blue-500 dark:text-blue-400">Technical Expertise →</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Explore my software development journey and technical stack</p>
        </a>
        <a href="/blog"
           class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-teal-500 dark:text-teal-400">Latest Insights →</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Read about my experiences and technical insights</p>
        </a>
        <a href="/software-development#github-projects"
           class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-purple-500 dark:text-purple-400">Featured Projects →</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">View my latest work and contributions</p>
        </a>
    </div>
</div>
