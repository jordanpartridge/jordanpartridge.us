<x-layouts.marketing>
    @volt('services.custom-development')
    <div class="min-h-screen bg-gradient-to-br from-gray-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white transition-colors duration-300">
        <div class="container mx-auto px-4 py-16">
            <!-- Breadcrumbs -->
            <x-ui.marketing.breadcrumbs :crumbs="[
                ['text' => 'Services', 'href' => '/services'],
                ['text' => 'Custom Development']
            ]" />

            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 mb-6">
                    Laravel Custom Development
                </h1>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Bespoke Laravel solutions built with military precision. From API integrations to complex business logic implementation, I'll architect and deploy robust applications that meet your specific objectives.
                </p>
            </div>

            <!-- Service Description -->
            <div class="mb-20 service-description">
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-gray-50 dark:from-purple-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-semibold text-purple-600 dark:text-purple-400 mb-6">Mission Parameters</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                            <p>
                                In the military, custom solutions are developed to address specific mission requirements that off-the-shelf options can't fulfill. Similarly, my Custom Development service creates tailor-made Laravel applications designed specifically for your unique business challenges and opportunities.
                            </p>
                            <h3>Benefits of Custom Laravel Development:</h3>
                            <ul>
                                <li><strong>Perfect Alignment with Business Needs:</strong> Solutions built specifically for your workflows and processes</li>
                                <li><strong>Competitive Advantage:</strong> Custom software that differentiates your business from competitors using off-the-shelf solutions</li>
                                <li><strong>Scalability by Design:</strong> Applications architected to grow with your business</li>
                                <li><strong>Integration Capabilities:</strong> Seamless connections with your existing systems and third-party services</li>
                                <li><strong>Ownership and Control:</strong> Full ownership of your custom software with no ongoing license fees</li>
                            </ul>
                            <p>
                                My approach to custom development combines military-grade planning and execution with agile methodologies. I work closely with you to understand your objectives, develop a strategic plan, and deliver a solution that exceeds expectations.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tiers -->
            <div class="mb-20 pricing-tiers">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 mb-10">
                    Service Packages
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Basic Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Tactical Solution</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$5,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / starting at</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Requirements analysis & planning
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Small-scale Laravel application
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Basic authentication & user roles
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Responsive design implementation
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Standard Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transform scale-105 z-10 relative transition-all duration-300 hover:shadow-xl">
                        <div class="bg-purple-600 p-4 border-b border-purple-700">
                            <h3 class="text-xl font-semibold text-center text-white">Strategic Application</h3>
                            <div class="absolute top-0 right-0 bg-yellow-500 text-xs font-bold px-3 py-1 rounded-bl-lg text-white">
                                RECOMMENDED
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$15,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / starting at</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Tactical Solution
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Medium-scale Laravel application
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Advanced authentication & permissions
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    API development & integration
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Comprehensive testing suite
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Package -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Enterprise Solution</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <span class="text-4xl font-bold text-gray-800 dark:text-gray-200">$30,000</span>
                                <span class="text-gray-600 dark:text-gray-400"> / starting at</span>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Everything in Strategic Application
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Large-scale enterprise application
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    High-performance architecture
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Advanced security implementation
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Deployment & maintenance support
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="/work-with-me" class="inline-block w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-300">
                                    Select Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="mb-20 process-steps">
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 mb-10">
                    The Development Process
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-gray-50 dark:from-purple-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">1</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Discovery & Planning</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            We begin with a thorough discovery phase to understand your business objectives, user needs, and technical requirements. This forms the foundation for a detailed project plan.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">2</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Architecture & Design</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I design a robust architecture that ensures scalability, security, and performance. This includes database schema design, API structure, and application architecture.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">3</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Development</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            Using agile methodologies, I develop your application in iterative cycles, providing regular updates and demonstrations of progress. This approach ensures flexibility and alignment with your vision.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">4</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Testing & Quality Assurance</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            Comprehensive testing is conducted throughout the development process, including unit tests, integration tests, and user acceptance testing to ensure a robust, bug-free application.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start mb-8">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">5</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Deployment</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            I handle the deployment process, ensuring a smooth transition to your production environment with minimal disruption. This includes server configuration, database migration, and application deployment.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 rounded-full p-3 mr-4">
                                        <span class="flex items-center justify-center w-8 h-8 text-xl font-bold text-purple-600 dark:text-purple-400">6</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Support & Maintenance</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            After deployment, I provide ongoing support and maintenance to ensure your application continues to perform optimally. This includes bug fixes, security updates, and feature enhancements.
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
                <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 mb-10">
                    Frequently Asked Questions
                </h2>
                <div class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-8 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-gray-50 dark:from-purple-900 dark:to-gray-900 opacity-10"></div>
                    <div class="relative z-10">
                        <div class="space-y-6">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-300">
                                    <span>How long does it take to develop a custom Laravel application?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        The timeline varies depending on the complexity and scope of your project. A small application might take 4-8 weeks, while a more complex enterprise solution could take 3-6 months or more. During our initial consultation, I'll provide a more specific timeline based on your requirements. I use agile methodologies to deliver value incrementally, so you'll see progress throughout the development process.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-300">
                                    <span>Do you provide ongoing support after the application is launched?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Yes, I offer ongoing support and maintenance packages to ensure your application continues to run smoothly after launch. This includes bug fixes, security updates, performance monitoring, and feature enhancements. I can also provide training for your team to help them manage and maintain the application effectively.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-300">
                                    <span>Can you integrate with existing systems and third-party services?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Absolutely. One of Laravel's strengths is its ability to integrate with a wide range of systems and services. I have extensive experience integrating with payment gateways, CRM systems, marketing automation tools, analytics platforms, and many other third-party services. During the discovery phase, we'll identify all the necessary integrations and develop a plan to implement them seamlessly.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-300">
                                    <span>How do you ensure the security of custom applications?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Security is a top priority in all my development work. I implement multiple layers of security, including:
                                        <br><br>
                                        - Secure authentication and authorization systems
                                        <br>
                                        - Protection against common vulnerabilities (CSRF, XSS, SQL injection, etc.)
                                        <br>
                                        - Data encryption for sensitive information
                                        <br>
                                        - Regular security updates and patches
                                        <br>
                                        - Comprehensive security testing
                                        <br><br>
                                        My military background has instilled in me a deep understanding of security principles, which I apply to every application I build.
                                    </p>
                                </div>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700">
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-lg text-gray-800 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-300">
                                    <span>What if I need to add features or make changes after the initial development?</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse>
                                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                                        Software is never truly "finished" – it evolves with your business. I design applications with future growth and changes in mind, using modular architecture and clean code practices that make it easier to add features or modify functionality. After the initial development, I can continue to work with you on an ongoing basis to implement new features, make improvements, and adapt the application as your business needs change.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <h2 class="text-3xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 mb-6">
                    Ready to Build Your Custom Laravel Solution?
                </h2>
                <p class="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Let's work together to create a custom Laravel application that perfectly aligns with your business needs and gives you a competitive edge. Book a consultation today to discuss your project.
                </p>
                <div class="relative group inline-block">
                    <a href="/work-with-me"
                       class="inline-block bg-gradient-to-r from-purple-500 to-blue-500 dark:from-purple-500 dark:to-blue-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-purple-500/50 dark:hover:shadow-purple-500/50 transition duration-300 relative overflow-hidden">
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
