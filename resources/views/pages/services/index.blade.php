<x-layouts.marketing>
    @volt('services')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-6">
                    Laravel Development Services
                </h1>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Elevate your web applications with expert Laravel development. I specialize in creating fast, scalable, and maintainable solutions that empower your business and delight your users.
                </p>
                <div class="flex justify-center">
                    <a href="/work-with-me" class="px-8 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        Book a Consultation
                    </a>
                </div>
            </div>

            <!-- Service Categories Grid -->
            <div class="mb-20">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-10">
                    Services Offered
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 service-grid">
                    <!-- Code Audit Service -->
                    <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden group hover:shadow-blue-500/50 dark:hover:shadow-blue-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-50 dark:from-blue-900 dark:to-gray-900 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-600 dark:text-blue-400">Code Audit</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Comprehensive assessment of your codebase to identify vulnerabilities, performance bottlenecks, and opportunities for optimization. I'll provide detailed recommendations to improve code quality and security.
                            </p>
                            <a href="/services/code-audit" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-300">
                                Learn More
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Performance Optimization Service -->
                    <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden group hover:shadow-green-500/50 dark:hover:shadow-green-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-gray-50 dark:from-green-900 dark:to-gray-900 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-4 text-green-600 dark:text-green-400">Performance Optimization</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Strategic enhancement of your application's speed, efficiency, and resource utilization. I'll optimize your database queries, implement caching strategies, and streamline your codebase for maximum performance.
                            </p>
                            <a href="/services/performance-optimization" class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium transition-colors duration-300">
                                Learn More
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Custom Development Service -->
                    <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden group hover:shadow-purple-500/50 dark:hover:shadow-purple-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-gray-50 dark:from-purple-900 dark:to-gray-900 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-4 text-purple-600 dark:text-purple-400">Custom Development</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Bespoke Laravel solutions tailored to your business needs. From API integrations to complex business logic implementation, I'll architect and deploy robust applications that meet your specific objectives.
                            </p>
                            <a href="/services/custom-development" class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-medium transition-colors duration-300">
                                Learn More
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Proof Section -->
            <div class="mb-20">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-10">
                    Client Testimonials
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 opacity-50"></div>
                    <div class="relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Testimonial 1 -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mr-4">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">S</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Scott Foley</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Database Engineer/Scott docs Tech Platform Owner</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 italic">
                                    "Doing business with Jordan was paramount! He helped optimize queries in a database I work on, and the speed and efficiency of the system is elite. His knowledge and strategic planning to elevate a business by demonstrating his technology expertise is a true asset."
                                </p>
                            </div>

                            <!-- Testimonial 2 -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mr-4">
                                        <span class="text-green-600 dark:text-green-400 font-bold text-xl">S</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Sarah Williams</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Founder, DataDrive</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 italic">
                                    "Working with Jordan was like having a strategic partner rather than just a developer. His code audit identified critical security vulnerabilities we had missed for months."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 mb-6">
                    Ready to Build Something Amazing?
                </h2>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Let's discuss how my Laravel development expertise can help transform your ideas into elegant, high-performing web applications.
                </p>
                <div class="relative group inline-block">
                    <a href="/work-with-me"
                       class="inline-block bg-gradient-to-r from-primary-500 to-secondary-500 dark:from-primary-600 dark:to-secondary-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-primary-500/50 dark:hover:shadow-secondary-500/50 transition duration-300 relative overflow-hidden">
                        <span class="relative z-10">Book a Consultation</span>
                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
