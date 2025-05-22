<?php

use function Laravel\Folio\name;

name('integrations.gmail-client');

?>

<x-layouts.app>
    <x-slot name="title">Gmail Integration Package - Laravel API Client</x-slot>
    <x-slot name="description">Enterprise-grade Gmail API integration for Laravel applications. Zero-configuration OAuth, robust email management, and seamless Laravel integration by Jordan Partridge.</x-slot>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 dark:from-primary-800 dark:via-primary-900 dark:to-gray-900">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30">
                        <i class="fas fa-envelope text-white mr-2"></i>
                        <span class="text-white font-medium">Laravel Gmail Integration</span>
                    </div>
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">
                    Gmail API Client
                </h1>

                <p class="max-w-3xl mx-auto text-xl text-primary-100 mb-4">
                    Enterprise-grade Gmail integration package for Laravel applications
                </p>

                <p class="max-w-2xl mx-auto text-lg text-primary-200 mb-8">
                    Built by <strong>Jordan Partridge</strong> - Laravel Integration Specialist & API Package Developer
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://github.com/partridgerocks/gmail_client"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <i class="fab fa-github mr-2"></i>
                        View on GitHub
                    </a>
                    <a href="/integrations"
                       class="inline-flex items-center px-6 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200">
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
            <div class="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 dark:bg-opacity-20 rounded-xl p-8 border border-blue-200 dark:border-blue-700 text-center">
                <div class="flex justify-center mb-4">
                    <i class="fas fa-envelope text-4xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-4">Gmail Integration Package - Active Development</h2>
                <p class="text-blue-700 dark:text-blue-300 mb-6">
                    Laravel package for Gmail API integration with OAuth 2.0 authentication, currently in active development and ready for use.
                </p>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 inline-block">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Status:</strong> In Development & Ready for Use<br>
                        <strong>Laravel Support:</strong> 10.x, 11.x, 12.x<br>
                        <strong>PHP Requirements:</strong> 8.2+<br>
                        <strong>Built With:</strong> Saloon PHP & Spatie Laravel Data
                    </p>
                </div>
            </div>

            <!-- Part of Integration Suite -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg border border-blue-200 dark:border-blue-800">
                <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Part of Jordan's Integration Package Suite</h3>
                <p class="text-blue-700 dark:text-blue-300 text-sm">
                    This Gmail client joins the <a href="/strava-client" class="underline hover:no-underline">Strava Client</a> and upcoming GitHub integration as part of a comprehensive suite of Laravel API integration packages, all designed with zero-configuration principles and enterprise-grade reliability.
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
                    Production-ready Gmail integration built with Saloon PHP and Spatie Laravel Data
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- OAuth 2.0 Authentication -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-key text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">OAuth 2.0 Authentication</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Built-in OAuth 2.0 authentication with built-in routes for secure Gmail API access and token management.
                    </p>
                </div>

                <!-- Email Operations -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-inbox text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Email Operations</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Read emails and threads, send emails with HTML content, support for CC and BCC with strongly-typed data objects.
                    </p>
                </div>

                <!-- Label Management -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tags text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Label Management</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Comprehensive label management system for organizing and categorizing emails with Laravel-style API methods.
                    </p>
                </div>

                <!-- Memory-Efficient Processing -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tachometer-alt text-orange-600 dark:text-orange-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance Optimized</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Memory-efficient processing with lazy loading for large datasets, pagination support, and customizable batch sizes.
                    </p>
                </div>

                <!-- Laravel Integration -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-layer-group text-teal-600 dark:text-teal-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laravel Integration</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Facade and dependency injection support with Laravel 10, 11, and 12 compatibility for seamless framework integration.
                    </p>
                </div>

                <!-- Developer-Friendly -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-code text-red-600 dark:text-red-400"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Developer-Friendly</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Built with Saloon PHP for API interactions and Spatie Laravel Data for structured data handling with full flexibility.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Philosophy -->
    <div class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                        Enterprise-Grade Email Integration
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                        The Gmail Integration Package embodies Jordan's philosophy of zero-configuration complexity with maximum functionality.
                        Designed for enterprise Laravel applications that require robust, scalable email integration.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Production-Ready Architecture</h3>
                                <p class="text-gray-600 dark:text-gray-300">Built for high-volume email processing with robust error handling and retry mechanisms.</p>
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
                                <p class="text-gray-600 dark:text-gray-300">Seamless integration with Laravel's ecosystem including Events, Jobs, and Collections.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Security-First Design</h3>
                                <p class="text-gray-600 dark:text-gray-300">Enterprise-grade security with encrypted token storage and audit trails.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 lg:mt-0">
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-8 text-white">
                        <h3 class="text-xl font-bold mb-4">Why Choose Jordan's Gmail Package?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-bolt text-yellow-300 mr-3"></i>
                                <span>Zero-configuration setup out of the box</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-shield-alt text-green-300 mr-3"></i>
                                <span>Enterprise-grade security and compliance</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-code text-blue-300 mr-3"></i>
                                <span>Laravel-native API design patterns</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-users text-purple-300 mr-3"></i>
                                <span>Multi-user OAuth token management</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Code Preview -->
    <div class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Planned API Design</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Simple, intuitive Laravel-style API for complex Gmail operations
                </p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Expected Usage Example</h3>
                </div>
                <div class="p-6">
                    <pre class="text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>// Zero-configuration Gmail client
$gmail = Gmail::for($user);

// Read emails with Laravel Collections
$emails = $gmail->emails()
    ->unread()
    ->from('client@example.com')
    ->limit(10)
    ->get();

// Send email with attachment
$gmail->send([
    'to' => 'recipient@example.com',
    'subject' => 'Project Update',
    'body' => 'Please find the latest project files attached.',
    'attachments' => [storage_path('reports/monthly.pdf')]
]);

// Webhook integration
$gmail->onNewEmail(function ($email) {
    ProcessIncomingEmail::dispatch($email);
});

// Label management
$gmail->labels()->create('Client Projects');
$gmail->emails()->labelAs('Client Projects');</code></pre>
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
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>
                        About Jordan
                    </a>
                    <a href="/integrations"
                       class="inline-flex items-center px-6 py-3 border-2 border-primary-600 text-base font-medium rounded-md text-primary-600 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <i class="fas fa-puzzle-piece mr-2"></i>
                        View All Packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>