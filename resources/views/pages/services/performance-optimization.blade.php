<x-layouts.marketing>
    @volt('services.performance-optimization')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Breadcrumbs -->
            <x-ui.marketing.breadcrumbs :crumbs="[
                ['text' => 'Services', 'href' => '/services'],
                ['text' => 'Performance Optimization']
            ]" />

            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-400 dark:to-blue-400 mb-6">
                    Laravel Performance Optimization
                </h1>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Strategic enhancement of your Laravel application's speed, efficiency, and resource utilization for maximum performance and scalability.
                </p>
            </div>

            <!-- Service Description -->
            <div class="mb-20 service-description">
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-gray-50 dark:from-green-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-semibold text-green-600 dark:text-green-400 mb-6">Mission Parameters</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                            <p>
                                In military operations, efficiency and speed can mean the difference between success and failure. The same is true for your Laravel application. My Performance Optimization service applies military-grade precision to identify and eliminate bottlenecks, streamline processes, and maximize your application's performance.
                            </p>
                            <h3>Benefits of Laravel Performance Optimization:</h3>
                            <ul>
                                <li><strong>Improved User Experience:</strong> Faster page loads and response times lead to higher user satisfaction and engagement</li>
                                <li><strong>Increased Conversion Rates:</strong> Studies show that even a 1-second delay in page load time can reduce conversions by 7%</li>
                                <li><strong>Better SEO Rankings:</strong> Search engines favor faster websites, improving your visibility</li>
                                <li><strong>Reduced Server Costs:</strong> Optimized applications require fewer server resources, potentially lowering your infrastructure costs</li>
                                <li><strong>Enhanced Scalability:</strong> Well-optimized applications can handle more users without performance degradation</li>
                            </ul>
                            <p>
                                My approach to performance optimization is methodical and comprehensive. I don't just apply generic "best practices" – I analyze your specific application, identify its unique bottlenecks, and develop targeted solutions that deliver measurable improvements.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tiers -->
            <div class="mb-20 pricing-tiers">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-400 dark:to-blue-400 mb-10">
                    Service Packages
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Basic Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Rapid Response</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$2,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Performance audit & analysis
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
                                    Basic caching implementation
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Detailed recommendations report
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Standard Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transform scale-105 z-10 relative transition-all duration-300 hover:shadow-xl">
                        <div class="bg-green-600 p-4 border-b border-green-700">
                            <h3 class="text-xl font-semibold text-center text-white">Tactical Optimization</h3>
                            <div class="absolute top-0 right-0 bg-yellow-500 text-xs font-bold px-3 py-1 rounded-bl-lg text-white">
                                RECOMMENDED
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$4,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Rapid Response
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Advanced caching strategies
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Asset optimization
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Implementation of key optimizations
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    2-hour consultation call
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Strategic Performance</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$7,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / project</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Tactical Optimization
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Full implementation of all optimizations
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Load testing & performance monitoring
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    8 hours of implementation support
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    60-day follow-up consultation
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="mb-20 process-steps">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-400 dark:to-blue-400 mb-10">
                    The Optimization Process
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-gray-50 dark:from-green-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">1</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Performance Baseline</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            We establish current performance metrics using tools like Laravel Telescope, Blackfire, and New Relic to create a baseline for measuring improvements.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">2</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Bottleneck Identification</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I conduct a comprehensive analysis to identify performance bottlenecks in your database queries, code execution, asset loading, and server configuration.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">3</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Optimization Strategy</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            Based on the analysis, I develop a prioritized optimization strategy that focuses on the most impactful improvements first, ensuring maximum ROI.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">4</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Implementation</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I implement the optimization strategy, which may include query optimization, caching implementation, code refactoring, asset optimization, and server configuration improvements.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">5</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Performance Verification</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            After implementation, I conduct thorough testing to measure the improvements against the baseline, ensuring that the optimizations have achieved the desired results.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-green-600 dark:text-green-400">6</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Knowledge Transfer</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I provide documentation and training to ensure your team understands the optimizations implemented and can maintain optimal performance going forward.
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
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-400 dark:to-blue-400 mb-10">
                    Frequently Asked Questions
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-gray-50 dark:from-green-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="space-y-6">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-300">
                                    <span>How much performance improvement can I expect?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        The level of improvement varies depending on your application's current state and the specific optimizations implemented. However, clients typically see page load time reductions of 30-70% and significant improvements in server response times. During our initial consultation, I can provide a more specific estimate based on your application's current performance.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-300">
                                    <span>Will optimizations require significant code changes?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Not necessarily. Many performance improvements can be achieved through configuration changes, query optimization, and caching strategies that don't require extensive code modifications. However, some optimizations may involve refactoring inefficient code. I always prioritize optimizations that provide the greatest performance gains with the least disruptive changes.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-300">
                                    <span>Do I need to take my application offline during optimization?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        No, most optimizations can be implemented without any downtime. I typically work in a staging environment first to test all changes before deploying to production. When changes are ready for production, they can be deployed using zero-downtime deployment strategies. For critical applications, I can schedule implementations during low-traffic periods to minimize any potential impact.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-300">
                                    <span>How do you measure performance improvements?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        I use a combination of tools and metrics to measure performance improvements, including page load times, time to first byte (TTFB), database query execution times, memory usage, and server response times. Before beginning any optimization work, I establish baseline metrics. After implementing optimizations, I compare the new metrics against the baseline to quantify the improvements. I also use tools like Lighthouse, WebPageTest, and New Relic to provide comprehensive performance analytics.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-300">
                                    <span>Will the optimizations affect my application's functionality?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        No, all optimizations are implemented with the primary goal of maintaining your application's existing functionality while improving its performance. I thoroughly test all changes to ensure they don't introduce any regressions or unintended side effects. If an optimization might affect functionality in any way, I'll discuss it with you before implementation so you can make an informed decision.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-400 dark:to-blue-400 mb-6">
                    Ready to Supercharge Your Laravel Application?
                </h2>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Let's work together to optimize your Laravel application for maximum performance. Book a consultation today to discuss your specific needs and how I can help.
                </p>
                <div class="relative group inline-block">
                    <a href="/work-with-me"
                       class="inline-block bg-gradient-to-r from-green-500 to-blue-500 dark:from-green-500 dark:to-blue-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-green-500/50 dark:hover:shadow-green-500/50 transition duration-300 relative overflow-hidden">
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
