<?php
// Integrations overview page
use function Livewire\Volt\{state};

state([]);

?>

<x-layouts.marketing>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950">
        <div class="absolute inset-0 opacity-10 bg-pattern-topo"></div>
        <div class="max-w-7xl mx-auto pt-16 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">Laravel Integration Packages</h1>
                <p class="max-w-3xl mx-auto text-xl text-primary-100 mb-4">
                    Production-ready Laravel packages that eliminate API integration complexity
                </p>
                <p class="max-w-2xl mx-auto text-lg text-primary-200 mb-8">
                    Built by <strong>Jordan Partridge</strong> - Laravel Integration Specialist
                </p>

                <!-- Integration Stats -->
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span class="text-white">Zero-Config Setup</span>
                    </div>
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-white">Enterprise-Grade</span>
                    </div>
                    <div class="px-4 py-2 bg-primary-700 bg-opacity-50 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-white">Developer-Loved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Integration Philosophy -->
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    The Integration Specialist's Philosophy
                </h2>
                <p class="max-w-3xl mx-auto text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Every API integration should be <strong>zero-configuration</strong>, <strong>bulletproof</strong>, and <strong>maintainable</strong>.
                    No more wrestling with OAuth flows, token management, or complex setup procedures.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Zero-Config Setup</h3>
                        <p class="text-gray-600 dark:text-gray-400">Install, configure credentials, start using. No routes, controllers, or complex setup required.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Bulletproof Reliability</h3>
                        <p class="text-gray-600 dark:text-gray-400">Automatic token refresh, retry logic, and comprehensive error handling built-in.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Laravel-Native</h3>
                        <p class="text-gray-600 dark:text-gray-400">Follows Laravel conventions with proper model relationships and service providers.</p>
                    </div>
                </div>
            </div>

            <!-- Featured Integration Packages -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-8">Featured Packages</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <!-- Strava Client -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                        <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2"></div>
                        <div class="p-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-biking text-2xl text-orange-600 dark:text-orange-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Strava Client</h3>
                                    <p class="text-sm text-gray-500">v0.3.3 • Fitness & Activity Tracking</p>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Zero-configuration OAuth integration with automatic token refresh. Connect user Strava accounts and fetch activities with just a few lines of code.
                            </p>
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Auto-registered OAuth routes
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Automatic token refresh
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Encrypted token storage
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="/integrations/strava-client" class="flex-1 bg-orange-600 text-white text-center py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors">
                                    View Documentation
                                </a>
                                <a href="https://github.com/jordanpartridge/strava-client" target="_blank" rel="noopener noreferrer" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    GitHub
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- GitHub Client -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-900 h-2"></div>
                        <div class="p-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fab fa-github text-2xl text-gray-800 dark:text-gray-200"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">GitHub Client</h3>
                                    <p class="text-sm text-gray-500">v0.3.1 • Repository & Development</p>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Professional GitHub API client built on Saloon with typed responses, pull request management, and comprehensive repository operations.
                            </p>
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Saloon-powered API client
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Typed DTOs & responses
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Pull request management
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="/integrations/github-client" class="flex-1 bg-gray-800 text-white text-center py-2 px-4 rounded-lg hover:bg-gray-900 transition-colors">
                                    View Documentation
                                </a>
                                <a href="https://github.com/jordanpartridge/github-client" target="_blank" rel="noopener noreferrer" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    GitHub
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Works in Progress -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-8">Works in Progress</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Gmail Client -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow opacity-75">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>
                        <div class="p-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fab fa-google text-2xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gmail Client</h3>
                                    <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">In Development • Email & Communication</p>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Enterprise-grade Gmail API integration for Laravel applications. OAuth 2.0 authentication, email operations, and label management.
                            </p>
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    OAuth 2.0 authentication
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Email sending & receiving
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Label & thread management
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="/integrations/gmail-client" class="flex-1 bg-gray-400 text-white text-center py-2 px-4 rounded-lg cursor-not-allowed">
                                    In Development
                                </a>
                                <a href="https://github.com/partridgerocks/gmail_client" target="_blank" rel="noopener noreferrer" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    GitHub
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Packagist Client -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow opacity-75">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-2"></div>
                        <div class="p-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-cube text-2xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Packagist Client</h3>
                                    <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">Early Alpha • Package Management</p>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Simple Laravel integration for Packagist API. Search packages, retrieve metadata, and integrate package discovery into your applications.
                            </p>
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Package search & discovery
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Package metadata retrieval
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Saloon-based architecture
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <span class="flex-1 bg-gray-400 text-white text-center py-2 px-4 rounded-lg cursor-not-allowed">
                                    Early Alpha
                                </span>
                                <a href="https://github.com/jordanpartridge/packagist-client" target="_blank" rel="noopener noreferrer" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    GitHub
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Choose These Packages -->
            <div class="bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900 dark:to-blue-900 dark:bg-opacity-20 rounded-xl p-8 border border-primary-200 dark:border-primary-800">
                <h2 class="text-2xl font-bold text-center mb-6">Why Choose Jordan's Integration Packages?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">95%</div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Less Code Required</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Compared to manual integration</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">5 Min</div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Average Setup Time</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">From install to working API</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">0</div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Maintenance Required</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Automatic token management</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">100%</div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Laravel Native</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Follows framework conventions</p>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="text-center mt-16">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Need a Custom Integration?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    I specialize in building production-ready Laravel API integrations for enterprise clients.
                </p>
                <a href="/contact" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Discuss Your Integration Needs
                </a>
            </div>
        </div>
    </div>

    <!-- Add structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Jordan Partridge",
        "jobTitle": "Laravel Integration Specialist",
        "description": "Laravel developer specializing in API integrations and enterprise package development",
        "url": "https://jordanpartridge.us/integrations",
        "sameAs": [
            "https://github.com/jordanpartridge"
        ]
    }
    </script>
</x-layouts.marketing>