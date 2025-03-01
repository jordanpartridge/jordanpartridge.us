<x-layouts.marketing>
    @volt('services.code-audit')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Breadcrumbs -->
            <x-ui.marketing.breadcrumbs :crumbs="[
                ['text' => 'Services', 'href' => '/services'],
                ['text' => 'Code Audit']
            ]" />

            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-6">
                    Laravel Code Audit Service
                </h1>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    A comprehensive assessment of your Laravel codebase to identify vulnerabilities, performance bottlenecks, and opportunities for optimization.
                </p>
            </div>

            <!-- Service Description -->
            <div class="mb-20 service-description">
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-50 dark:from-blue-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-semibold text-blue-600 dark:text-blue-400 mb-6">Mission Parameters</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                            <p>
                                Just as military reconnaissance provides critical intelligence before an operation, a code audit gives you a complete understanding of your application's strengths and vulnerabilities. My Laravel Code Audit service provides a thorough assessment of your codebase, identifying potential issues before they become critical problems.
                            </p>
                            <h3>Benefits of a Laravel Code Audit:</h3>
                            <ul>
                                <li><strong>Security Enhancement:</strong> Identify and address vulnerabilities before they can be exploited</li>
                                <li><strong>Performance Optimization:</strong> Discover bottlenecks and inefficiencies that slow down your application</li>
                                <li><strong>Code Quality Improvement:</strong> Ensure adherence to Laravel best practices and coding standards</li>
                                <li><strong>Technical Debt Reduction:</strong> Identify areas of technical debt and develop strategies to address them</li>
                                <li><strong>Architecture Assessment:</strong> Evaluate your application's architecture for scalability and maintainability</li>
                            </ul>
                            <p>
                                My military background has instilled in me the importance of thorough reconnaissance and attention to detail. I bring this same level of precision and thoroughness to every code audit, ensuring no vulnerability goes undetected.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tiers -->
            <div class="mb-20 pricing-tiers">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                    Service Packages
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Basic Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Reconnaissance</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$1,500</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Security vulnerability assessment
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Code quality review
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Basic performance assessment
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Comprehensive report
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Standard Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transform scale-105 z-10 relative transition-all duration-300 hover:shadow-xl">
                        <div class="bg-blue-600 p-4 border-b border-blue-700">
                            <h3 class="text-xl font-semibold text-center text-white">Tactical Assessment</h3>
                            <div class="absolute top-0 right-0 bg-yellow-500 text-xs font-bold px-3 py-1 rounded-bl-lg text-white">
                                RECOMMENDED
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$3,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Reconnaissance
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    In-depth performance analysis
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Database query optimization
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Architecture review
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    1-hour consultation call
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Strategic Analysis</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$5,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Tactical Assessment
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Scalability assessment
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Detailed remediation plan
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    4 hours of implementation support
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    30-day follow-up consultation
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="mb-20 process-steps">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                    The Code Audit Process
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-50 dark:from-blue-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">1</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Initial Briefing</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            We begin with a thorough discussion of your application, its purpose, and your specific concerns. This helps me understand the context and focus the audit on your most critical areas.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">2</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Codebase Access</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            You provide access to your codebase, typically through a Git repository. I'll also need access to your development environment to understand how the application runs in context.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">3</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Comprehensive Analysis</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I conduct a thorough analysis of your codebase, examining architecture, security, performance, and code quality. This includes both automated scanning and manual code review.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">4</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Findings Documentation</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I document all findings, categorizing issues by severity and type. Each issue is described in detail with code references and recommendations for remediation.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">5</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Report Delivery</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            You receive a comprehensive report detailing all findings, recommendations, and a prioritized action plan. The report includes both technical details and executive summary.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-blue-600 dark:text-blue-400">6</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Debrief and Next Steps</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            We schedule a call to review the findings and discuss implementation strategies. Depending on your package, I can provide implementation support or schedule follow-up consultations.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mb-20 faq-section">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
                    Frequently Asked Questions
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-50 dark:from-blue-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="space-y-6">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                    <span>How long does a code audit typically take?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        The duration depends on the size and complexity of your codebase. A typical audit for a medium-sized Laravel application takes 1-2 weeks. Larger applications or those with complex architectures may take longer. I'll provide a specific timeline estimate after our initial consultation.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                    <span>Do I need to provide access to my production environment?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        No, I typically work with a development or staging environment. Access to your codebase (usually via Git) and a development environment is sufficient. If production-specific issues need to be investigated, we can discuss secure ways to gather that information without compromising your production environment.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                    <span>What if you find critical security vulnerabilities?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        If I discover critical security vulnerabilities, I'll notify you immediately rather than waiting for the final report. This allows you to take immediate action to address the issue. I can also provide emergency guidance on mitigating the vulnerability until a permanent fix can be implemented.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                    <span>Can you help implement the recommended changes?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Yes, I offer implementation support as part of the Strategic Analysis package. If you need additional implementation assistance beyond what's included in your package, we can arrange that as a separate engagement. I can either implement the changes myself or work with your development team to guide them through the implementation process.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                    <span>Is my code and business information kept confidential?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Absolutely. I treat all client code and business information with the utmost confidentiality. Before beginning the audit, we'll sign a Non-Disclosure Agreement (NDA) to legally protect your intellectual property and business information. My military background has instilled in me a deep respect for confidentiality and security protocols.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-6">
                    Ready to Secure Your Laravel Application?
                </h2>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Let's work together to identify and address vulnerabilities in your Laravel application. Book a consultation today to discuss your specific needs and how I can help.
                </p>
                <div class="relative group inline-block">
                    <a href="/work-with-me"
                       class="inline-block bg-gradient-to-r from-blue-500 to-green-500 dark:from-purple-500 dark:to-pink-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300 relative overflow-hidden">
                        <span class="relative z-10">Book a Consultation</span>
                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                    </a>
                    <span class="absolute top-full left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 mt-2 whitespace-nowrap">
                        Initiate Contact Protocol
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
