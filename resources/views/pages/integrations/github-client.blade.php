<?php

use function Laravel\Folio\name;

name('integrations.github-client');

?>

<x-layouts.app>
    <x-slot name="title">GitHub Client - Laravel API Integration Package</x-slot>
    <x-slot name="description">Professional GitHub API integration for Laravel applications. Built on Saloon with typed responses, pull request management, and comprehensive repository operations by Jordan Partridge.</x-slot>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-gray-800 via-gray-900 to-black">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30">
                        <i class="fab fa-github text-white mr-2"></i>
                        <span class="text-white font-medium">Laravel GitHub Integration</span>
                    </div>
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">
                    GitHub Client
                </h1>

                <p class="max-w-3xl mx-auto text-xl text-gray-100 mb-4">
                    Professional GitHub API integration package for Laravel applications
                </p>

                <p class="max-w-2xl mx-auto text-lg text-gray-200 mb-8">
                    Built by <strong>Jordan Partridge</strong> - Laravel Integration Specialist & API Package Developer
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://github.com/jordanpartridge/github-client"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="View Laravel GitHub Client package on GitHub"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fab fa-github mr-2"></i>
                        View on GitHub
                    </a>
                    <a href="/integrations"
                       class="inline-flex items-center px-6 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200">
                        <i class="fas fa-puzzle-piece mr-2"></i>
                        All Integration Packages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900 dark:to-blue-900 dark:bg-opacity-20 rounded-xl p-8 border border-green-200 dark:border-green-700 text-center">
                <div class="flex justify-center mb-4">
                    <i class="fab fa-github text-4xl text-green-600 dark:text-green-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-900 dark:text-green-100 mb-4">GitHub Client Package - Available Now</h2>
                <p class="text-green-700 dark:text-green-300 mb-6">
                    Production-ready Laravel package for GitHub API integration, actively maintained and ready for use.
                </p>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 inline-block">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Current Version:</strong> v0.3.1<br>
                        <strong>Status:</strong> Active Development & Maintenance<br>
                        <strong>Laravel Support:</strong> 10.x, 11.x, 12.x<br>
                        <strong>PHP Requirements:</strong> 8.2+<br>
                        <strong>Built With:</strong> Saloon PHP & Modern Architecture
                    </p>
                </div>
            </div>

            <!-- Part of Integration Suite -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Part of Jordan's Integration Package Suite</h3>
                <p class="text-blue-700 dark:text-blue-300 text-sm">
                    This GitHub client joins the <a href="/strava-client" class="underline hover:no-underline">Strava Client</a> as part of a comprehensive suite of Laravel API integration packages, all designed with zero-configuration principles and enterprise-grade reliability.
                </p>
            </div>
        </div>
    </div>

    <!-- Current Features -->
    <div class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Current Features</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Production-ready GitHub integration built with Saloon and Laravel best practices
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Saloon-Powered API Client -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rocket text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Saloon-Powered</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Built on Saloon PHP for robust API handling with typed responses using data transfer objects and Laravel-style resource patterns.
                    </p>
                </div>

                <!-- Repository Management -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-code-branch text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Repository Operations</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        List and retrieve repositories, manage commits, and handle comprehensive GitHub API interactions with clean Laravel syntax.
                    </p>
                </div>

                <!-- Pull Request Management -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-code-branch text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pull Request Management</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Create, update, and merge pull requests with full support for reviews and comments through a clean Laravel interface.
                    </p>
                </div>

                <!-- Laravel Integration -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-layer-group text-orange-600 dark:text-orange-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laravel Integration</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Supports facades and dependency injection with Laravel 10, 11, and 12 compatibility for seamless framework integration.
                    </p>
                </div>

                <!-- Typed Responses -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-alt text-teal-600 dark:text-teal-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Typed Responses</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Strongly-typed data transfer objects ensure type safety and better IDE support for all GitHub API responses.
                    </p>
                </div>

                <!-- Easy Installation -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-download text-red-600 dark:text-red-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Simple Setup</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Install via Composer, add your GitHub token to .env, and start using the GitHub API with clean Laravel syntax immediately.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Installation & Quick Start -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Quick Start</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Get started with GitHub API integration in minutes
                </p>
            </div>

            <div class="bg-gray-900 rounded-xl p-8 mb-8">
                <h3 class="text-xl font-bold text-white mb-4">Installation</h3>
                <pre class="text-green-400 text-sm overflow-x-auto"><code>composer require jordanpartridge/github-client</code></pre>
            </div>

            <div class="bg-gray-900 rounded-xl p-8">
                <h3 class="text-xl font-bold text-white mb-4">Configuration & Usage</h3>
                <pre class="text-green-400 text-sm overflow-x-auto"><code># Add GitHub token to .env
GITHUB_TOKEN=your_github_token_here

# Use with Facade
use Partridge\GitHub\GitHub;

$repositories = GitHub::repositories()->list();

# Use with Dependency Injection
public function __construct(
    private readonly GitHub $github
) {}

$pullRequests = $this->github->pullRequests()
    ->list($owner, $repo);

# Repository operations
$repo = GitHub::repositories()->get('jordanpartridge', 'github-client');

# Pull request management
$pr = GitHub::pullRequests()->create($owner, $repo, [
    'title' => 'Feature: Add new functionality',
    'head' => 'feature-branch',
    'base' => 'main',
    'body' => 'This PR adds amazing new features'
]);

# Add PR review
GitHub::pullRequests()->addReview($owner, $repo, $prNumber, [
    'body' => 'Looks good to me!',
    'event' => 'APPROVE'
]);</code></pre>
            </div>
        </div>
    </div>

    <!-- Integration Philosophy -->
    <div class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                        Professional GitHub Integration
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                        The GitHub Client Package embodies Jordan's philosophy of eliminating integration complexity while maximizing developer productivity.
                        Designed for professional Laravel applications that require robust, scalable GitHub integration.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Developer-Focused Design</h3>
                                <p class="text-gray-600 dark:text-gray-300">Built for modern development workflows with intuitive API design and comprehensive documentation.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Laravel-Native Integration</h3>
                                <p class="text-gray-600 dark:text-gray-300">Seamless integration with Laravel's ecosystem including Eloquent models, Jobs, and Events.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Enterprise-Ready Architecture</h3>
                                <p class="text-gray-600 dark:text-gray-300">Production-tested patterns with robust error handling and scalable design principles.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 lg:mt-0">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-xl p-8 text-white">
                        <h3 class="text-xl font-bold mb-4">Why Choose Jordan's GitHub Package?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-bolt text-yellow-300 mr-3"></i>
                                <span>Zero-configuration setup out of the box</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-shield-alt text-green-300 mr-3"></i>
                                <span>Secure token management built-in</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-code text-blue-300 mr-3"></i>
                                <span>Laravel-native API design patterns</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-users text-purple-300 mr-3"></i>
                                <span>Multi-user repository access control</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About the Developer -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Built by Jordan Partridge
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-8">
                    Laravel Integration Specialist with extensive experience building production-grade API integration packages.
                    Jordan's packages are used by teams worldwide to eliminate integration complexity and accelerate development.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/about"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>
                        About Jordan
                    </a>
                    <a href="/integrations"
                       class="inline-flex items-center px-6 py-3 border-2 border-gray-800 text-base font-medium rounded-md text-gray-800 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-puzzle-piece mr-2"></i>
                        View All Packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>