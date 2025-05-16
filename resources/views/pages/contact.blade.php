<x-layouts.app
    title="Contact Me | Jordan Partridge"
    metaDescription="Get in touch with Jordan Partridge for Laravel development, consulting, and custom web applications."
>
    @volt('contact')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Breadcrumbs -->
            <div class="mb-8 max-w-7xl mx-auto">
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <a href="/" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700 dark:text-gray-300">Contact</span>
                </div>
            </div>

            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-primary-600 dark:text-primary-400 mb-6">
                    Get in Touch
                </h1>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Have a project in mind or want to discuss how we can work together? Fill out the form below and I'll get back to you as soon as possible.
                </p>
            </div>

            <!-- Contact Form Section -->
            <div class="max-w-6xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                        <!-- Left Column: Contact Info -->
                        <div class="p-8 bg-gradient-to-br from-primary-500/10 to-primary-600/10 dark:from-primary-900/30 dark:to-primary-800/30">
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Contact Information</h2>

                            <div class="space-y-8">
                                <!-- Email -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/50 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Email</h3>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            <a href="mailto:jordan@partridge.rocks" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                                                jordan@partridge.rocks
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                <!-- LinkedIn -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/50 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">LinkedIn</h3>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            <a href="https://www.linkedin.com/in/jordan-partridge-8284897/" target="_blank" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                                                Connect on LinkedIn
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                <!-- Calendly -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-900/50 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Schedule a Call</h3>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            <a href="https://calendly.com/jordan-partridge/30min" target="_blank" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                                                Book a 30-minute consultation
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Decorative Image -->
                            <div class="mt-12 hidden lg:block">
                                <img src="{{ asset('img/contact-illustration.svg') }}" alt="Contact illustration" class="w-full max-w-xs mx-auto opacity-80" onerror="this.style.display='none'">
                            </div>
                        </div>

                        <!-- Right Column: Form -->
                        <div class="p-8 lg:col-span-2">
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Send a Message</h2>
                            <x-ui.contact-form />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials/Trust Badges Section -->
            <div class="max-w-6xl mx-auto mt-20">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Trusted By</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('img/clients/goodwill.png') }}" alt="Goodwill" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                    </div>
                    <div class="flex justify-center">
                        <img src="{{ asset('img/clients/pstrax.png') }}" alt="PSTrax" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                    </div>
                    <div class="flex justify-center">
                        <img src="{{ asset('img/clients/atmosol.png') }}" alt="Atmosol" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                    </div>
                    <div class="flex justify-center">
                        <img src="{{ asset('img/clients/insight.png') }}" alt="Insight" class="h-12 opacity-75 dark:opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>