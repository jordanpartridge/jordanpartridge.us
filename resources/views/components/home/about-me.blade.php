<div x-data="{ expanded: false, darkMode: false }"
     x-init="darkMode = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)"
     :class="{ 'dark': darkMode }"
     class="mt-8 space-y-6 transition-colors duration-300 ease-in-out">
    <h2 class="text-4xl font-extrabold text-center bg-gradient-to-r from-blue-600 to-teal-400 text-transparent bg-clip-text transition-all duration-300">About Me: From Signal Corps to Software Engineering</h2>

    <div class="flex flex-col items-center justify-center text-center space-y-4 mb-8">
        <div class="relative inline-block">
            <img src="img/Signal Corps Insignia.png" alt="U.S. Army Signal Corps Insignia" class="w-32 h-32 drop-shadow-[0_0_0.75rem_rgba(0,0,0,0.5)] transition-all duration-300 hover:scale-105">
        </div>
        <div class="space-y-2">
            <p class="text-2xl italic font-semibold text-yellow-600 dark:text-yellow-400 drop-shadow-[0_2px_2px_rgba(0,0,0,0.3)] transition-colors duration-300">
                "Pro Patria Vigilans"
            </p>
            <p class="text-lg text-blue-600 dark:text-blue-300 transition-colors duration-300">
                Watchful for the Country
            </p>
        </div>
    </div>

    <div class="text-lg text-gray-700 dark:text-gray-200 leading-relaxed transition-colors duration-300">
        <p>Picture this: a <span class="text-green-600 dark:text-green-400 font-semibold">kid tinkering</span> with BASIC on an MS-DOS computer, fast-forwarding through <span class="text-yellow-600 dark:text-yellow-300 font-semibold">four years in the United States Army Signal Corps</span>, and landing as a <span class="text-blue-600 dark:text-blue-400 font-semibold">full-stack software engineer</span> with over a decade of experience. That's me – <span class="text-pink-600 dark:text-pink-400 font-bold text-xl">Jordan Partridge</span>, your friendly neighborhood <span class="text-purple-600 dark:text-purple-400 font-semibold">code warrior</span> with a military-grade background in communications!</p>

        <div x-show="expanded" x-collapse>
            <p class="mt-4">My stint in the Signal Corps wasn't just about keeping radios humming. As a <span class="text-orange-600 dark:text-orange-400 font-semibold">communication specialist</span>, I was the mastermind behind our unit's <span class="text-cyan-600 dark:text-cyan-400 font-semibold">communication plans</span>. I learned that in both military ops and software development, <span class="text-red-600 dark:text-red-400 font-semibold">redundancy isn't just a buzzword—it's a lifeline</span>. Whether it's planning multiple comm routes for a mission or designing fault-tolerant systems, I've got <span class="text-green-600 dark:text-green-300 font-semibold">backup plans for my backup plans</span>.</p>

            <p class="mt-4">In the Army, my role went beyond just keeping the lines open. I was the <span class="text-yellow-600 dark:text-yellow-400 font-semibold">go-to troubleshooter</span> for complex systems, the <span class="text-blue-600 dark:text-blue-300 font-semibold">guardian of secure networks</span>, and the ensure of timely information delivery. Fast forward to today, and you'll find me doing much the same—albeit with <span class="text-pink-600 dark:text-pink-300 font-semibold">less camo and more caffeine</span>. I navigate intricate codebases, fortify data security, and optimize information flow in applications with the precision of a <span class="text-purple-600 dark:text-purple-300 font-semibold">well-executed military operation</span>.</p>
        </div>
    </div>

    <button
        @click="expanded = !expanded"
        class="mt-6 px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 text-white rounded-full hover:from-blue-600 hover:to-teal-500 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 shadow-lg"
        x-text="expanded ? 'Read Less' : 'Read More'"
        aria-expanded="expanded"
    ></button>
    <p class="mt-4">
        <a href="/software-development" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">
            Learn more about my software development expertise →
        </a>
    </p>
</div>
