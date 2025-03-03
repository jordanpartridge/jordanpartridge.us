@props(['username' => 'jordanpartridge'])

<div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-lg backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-primary-500/20 transition-all duration-300">
    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500 dark:text-primary-400 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
        </svg>
        GitHub Contributions
    </h3>

    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
        <img
            src="https://ghchart.rshah.org/{{ $username }}"
            alt="{{ $username }}'s GitHub Contribution Chart"
            class="w-full h-auto"
            loading="lazy"
        />
    </div>

    <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <span class="font-medium">View my contribution activity and coding activity on GitHub</span>
        </div>
        <a
            href="https://github.com/{{ $username }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors font-medium"
        >
            @{{ $username }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
        </a>
    </div>
</div>