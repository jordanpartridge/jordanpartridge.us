<div class="mb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-tr from-primary-50/30 to-secondary-50/30 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-3xl transform rotate-1 scale-105 -z-10"></div>
    <div class="relative z-10">
        <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-2">
            GitHub Arsenal
        </h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">Open-source projects and real-world code deployments</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <x-software-development.github-repo-card
            name="packagist-client"
            description="Simple integration library for the Packagist API, allowing seamless access to PHP package information, downloads, and statistics."
            :stars="8"
            :forks="2"
            :technologies="['PHP', 'Laravel', 'Composer', 'API Integration']"
            updated="Feb 12, 2025"
            url="https://github.com/jordanpartridge/packagist-client"
        />

        <x-software-development.github-repo-card
            name="user-make"
            description="Laravel package that extends the built-in user management with advanced role-based permissions, team capabilities, and custom authentication options."
            :stars="12"
            :forks="4"
            :technologies="['Laravel', 'PHP', 'Authentication', 'Authorization']"
            updated="Jan 8, 2025"
            url="https://github.com/jordanpartridge/user-make"
        />

        <x-software-development.github-repo-card
            name="mary-ui-bootcamp"
            description="A comprehensive guide to building modern interfaces using Mary UI, with real-world examples and best practices from production applications."
            :stars="6"
            :forks="3"
            :technologies="['JavaScript', 'UI/UX', 'CSS', 'Mary UI']"
            updated="Oct 31, 2024"
            url="https://github.com/jordanpartridge/mary-ui-bootcamp"
        />

        <x-software-development.github-repo-card
            name="laravel-deployment-toolkit"
            description="An all-in-one solution for deploying Laravel applications to different environments with automated testing, database migrations, and rollback capabilities."
            :stars="15"
            :forks="7"
            :technologies="['Laravel', 'DevOps', 'CI/CD', 'Bash']"
            updated="Dec 25, 2024"
            url="https://github.com/jordanpartridge/laravel-deployment-toolkit"
        />
    </div>
</div>
