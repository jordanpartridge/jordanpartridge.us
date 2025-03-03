@php
$repositories = \App\Models\GithubRepository::featured()
    ->orderBy('display_order')
    ->orderByDesc('stars_count')
    ->get();

$settings = app(\App\Settings\GitHubSettings::class);
$username = $settings->username ?? 'jordanpartridge';
@endphp

<div class="mb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-tr from-primary-50/30 to-secondary-50/30 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-3xl transform rotate-1 scale-105 -z-10"></div>
    <div class="relative z-10">
        <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-2">
            GitHub Arsenal
        </h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-2 max-w-2xl mx-auto">Open-source projects and real-world code deployments</p>
        <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 mx-auto mb-8 rounded-full"></div>
    </div>

    {{-- GitHub Contribution Graph --}}
    <div class="mb-12">
        <x-software-development.github-contributions :username="$username" />
    </div>

    {{-- Featured Repositories --}}
    <div class="mt-12">
        <h3 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-200 mb-8">
            Featured Repositories
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse ($repositories as $repo)
                <x-software-development.github-repo-card
                    :name="$repo->name"
                    :description="$repo->description"
                    :stars="$repo->stars_count"
                    :forks="$repo->forks_count"
                    :technologies="$repo->technologies"
                    :updated="$repo->last_fetched_at ? $repo->last_fetched_at->format('M d, Y') : ''"
                    :url="$repo->url"
                />
            @empty
                {{-- Fallback to hardcoded repositories if none are in the database --}}
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
                    name="github-client"
                    description="An elegant GitHub API integration built for Laravel, providing a clean interface for accessing repositories, issues, pull requests, and more."
                    :stars="10"
                    :forks="3"
                    :technologies="['Laravel', 'PHP', 'GitHub API', 'API Client']"
                    updated="Mar 1, 2025"
                    url="https://github.com/jordanpartridge/github-client"
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
            @endforelse
        </div>
    </div>

    <div class="mt-10 text-center">
        <a href="https://github.com/{{ $username }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
            </svg>
            Follow on GitHub
        </a>
    </div>
</div>
