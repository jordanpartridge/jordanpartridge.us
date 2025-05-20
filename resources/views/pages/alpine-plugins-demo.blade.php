<x-layouts.app
    title="Alpine.js Plugins | Jordan Partridge"
    metaDescription="Demonstration of Alpine.js plugins implemented for interactive UI components"
>
    <div class="max-w-5xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-blue-500 dark:from-primary-400 dark:to-blue-400 mb-4">
                Alpine.js Plugins
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                A showcase of interactive UI components built with Alpine.js plugins
            </p>
        </div>

        <!-- Collapse Plugin Section -->
        <section class="mb-20">
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Collapse Plugin</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    The collapse plugin provides smooth height animations when expanding or collapsing elements.
                </p>
            </div>

            <!-- Basic Collapsible Component -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Basic Collapsible</h3>

                <div class="max-w-3xl border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <div x-data="{ open: false }" class="mb-1">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">Click to expand/collapse</span>
                            <svg class="h-5 w-5 transform transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="px-4 py-3 bg-white dark:bg-gray-900">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                This is a basic collapsible component using the Alpine.js collapse plugin. It animates the height
                                smoothly when expanding and collapsing.
                            </p>
                            <p class="text-gray-600 dark:text-gray-300">
                                You can place any content inside this collapsible section, including text, images, forms, or other components.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion Component -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Accordion</h3>

                <div class="max-w-3xl" x-data="{ activePanel: null }">
                    <div class="mb-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <button @click="activePanel = activePanel === 'panel1' ? null : 'panel1'" class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">What is Alpine.js?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-200" :class="{'rotate-180': activePanel === 'panel1'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="activePanel === 'panel1'" x-collapse class="px-4 py-3 bg-white dark:bg-gray-900">
                            <p class="text-gray-600 dark:text-gray-300">
                                Alpine.js is a rugged, minimal framework for composing JavaScript behavior in your markup. It offers the reactive and declarative nature of frameworks like Vue or React at a much lower cost. You can use it to add a sprinkle of JavaScript behavior to your HTML without building a dedicated JavaScript application.
                            </p>
                        </div>
                    </div>

                    <div class="mb-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <button @click="activePanel = activePanel === 'panel2' ? null : 'panel2'" class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">How does the collapse plugin work?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-200" :class="{'rotate-180': activePanel === 'panel2'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="activePanel === 'panel2'" x-collapse class="px-4 py-3 bg-white dark:bg-gray-900">
                            <p class="text-gray-600 dark:text-gray-300">
                                The collapse plugin measures the height of the element and then animates it from 0 to full height (or vice versa) when toggling. It adds proper transition styling automatically and handles overflow to ensure a smooth animation effect.
                            </p>
                        </div>
                    </div>

                    <div class="mb-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <button @click="activePanel = activePanel === 'panel3' ? null : 'panel3'" class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-left">
                            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">Can I customize the animation?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-200" :class="{'rotate-180': activePanel === 'panel3'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="activePanel === 'panel3'" x-collapse class="px-4 py-3 bg-white dark:bg-gray-900">
                            <p class="text-gray-600 dark:text-gray-300">
                                Yes! You can customize the duration of the animation using modifiers. For example, <code>x-collapse.duration.500ms</code> will set the animation duration to 500ms instead of the default 300ms.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Intersect Plugin Section -->
        <section class="mb-20">
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Intersect Plugin</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    The intersect plugin allows you to detect when an element enters or leaves the viewport.
                </p>
            </div>

            <!-- Scroll-triggered Animations -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Scroll-triggered Animations</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform transition-all duration-500 opacity-0 translate-y-8"
                        x-data="{}"
                        x-intersect.once="$el.classList.add('opacity-100', 'translate-y-0')"
                    >
                        <div class="h-40 bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Fade In on Scroll</h4>
                            <p class="text-gray-600 dark:text-gray-300">
                                This card fades in and moves up when it enters the viewport.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform transition-all duration-500 opacity-0 translate-y-8"
                        x-data="{}"
                        x-intersect.once.threshold.25="$el.classList.add('opacity-100', 'translate-y-0')"
                        style="transition-delay: 200ms;"
                    >
                        <div class="h-40 bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Staggered Animation</h4>
                            <p class="text-gray-600 dark:text-gray-300">
                                This card has a delay for a staggered animation effect.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform transition-all duration-500 opacity-0 translate-y-8"
                        x-data="{}"
                        x-intersect.once="$el.classList.add('opacity-100', 'translate-y-0')"
                        style="transition-delay: 400ms;"
                    >
                        <div class="h-40 bg-gradient-to-r from-indigo-500 to-indigo-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Longer Delay</h4>
                            <p class="text-gray-600 dark:text-gray-300">
                                This card has an even longer delay for a cascading effect.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>