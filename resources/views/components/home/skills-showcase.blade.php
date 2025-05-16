@props(['animated' => true])

@php
$skills = [
    'backend' => [
        ['name' => 'Laravel', 'level' => 5, 'icon' => 'fab fa-laravel', 'description' => 'Expert-level framework knowledge and architecture'],
        ['name' => 'PHP 8.4', 'level' => 5, 'icon' => 'fab fa-php', 'description' => 'Latest features and modern syntax optimizations'],
        ['name' => 'MySQL', 'level' => 4, 'icon' => 'fas fa-database', 'description' => 'Complex queries and optimizations'],
        ['name' => 'API Development', 'level' => 4, 'icon' => 'fas fa-exchange-alt', 'description' => 'RESTful and GraphQL endpoints'],
        ['name' => 'Filament Admin', 'level' => 5, 'icon' => 'fas fa-columns', 'description' => 'Custom panels and resources'],
    ],
    'frontend' => [
        ['name' => 'Livewire', 'level' => 5, 'icon' => 'fas fa-bolt', 'description' => 'Dynamic interfaces without JavaScript complexity'],
        ['name' => 'Alpine.js', 'level' => 5, 'icon' => 'fas fa-mountain', 'description' => 'Lightweight interactivity and animations'],
        ['name' => 'Tailwind CSS', 'level' => 5, 'icon' => 'fab fa-css3', 'description' => 'Custom designs and responsive layouts'],
        ['name' => 'Vue.js', 'level' => 3, 'icon' => 'fab fa-vuejs', 'description' => 'Component-based UI development'],
        ['name' => 'JavaScript', 'level' => 4, 'icon' => 'fab fa-js', 'description' => 'Modern ES6+ features and DOM manipulation'],
    ],
    'integrations' => [
        ['name' => 'Strava API', 'level' => 5, 'icon' => 'fas fa-biking', 'description' => 'Activity tracking and performance metrics'],
        ['name' => 'GitHub API', 'level' => 4, 'icon' => 'fab fa-github', 'description' => 'Repository management and automation'],
        ['name' => 'Google APIs', 'level' => 4, 'icon' => 'fab fa-google', 'description' => 'Gmail integration and OAuth workflows'],
        ['name' => 'Webhooks', 'level' => 4, 'icon' => 'fas fa-plug', 'description' => 'Event-driven integrations and callbacks'],
    ],
    'devops' => [
        ['name' => 'Git & GitHub', 'level' => 4, 'icon' => 'fab fa-git-alt', 'description' => 'Version control and collaboration'],
        ['name' => 'CI/CD', 'level' => 3, 'icon' => 'fas fa-sync-alt', 'description' => 'Automated testing and deployment'],
        ['name' => 'Docker', 'level' => 3, 'icon' => 'fab fa-docker', 'description' => 'Containerized development environments'],
        ['name' => 'Laravel Forge', 'level' => 4, 'icon' => 'fas fa-server', 'description' => 'Server provisioning and management'],
    ],
    'testing' => [
        ['name' => 'PHPUnit', 'level' => 4, 'icon' => 'fas fa-vial', 'description' => 'Comprehensive test coverage'],
        ['name' => 'Pest', 'level' => 4, 'icon' => 'fas fa-check-circle', 'description' => 'Expressive, modern testing framework'],
        ['name' => 'Laravel Dusk', 'level' => 3, 'icon' => 'fas fa-moon', 'description' => 'Browser automation for UI testing'],
    ]
];

$categories = [
    'backend' => ['name' => 'Backend Development', 'icon' => 'fas fa-server text-blue-500', 'description' => 'Building robust foundations and APIs', 'color' => 'blue'],
    'frontend' => ['name' => 'Frontend Development', 'icon' => 'fas fa-code text-teal-500', 'description' => 'Creating beautiful user interfaces', 'color' => 'teal'],
    'integrations' => ['name' => 'API Integrations', 'icon' => 'fas fa-plug text-amber-500', 'description' => 'Connecting with third-party services', 'color' => 'amber'],
    'devops' => ['name' => 'DevOps & Deployment', 'icon' => 'fas fa-cogs text-purple-500', 'description' => 'Automating infrastructure and delivery', 'color' => 'purple'],
    'testing' => ['name' => 'Testing & Quality', 'icon' => 'fas fa-check-square text-green-500', 'description' => 'Ensuring reliability and correctness', 'color' => 'green'],
];
@endphp

<section id="expertise" class="py-20 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <h2 class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-6 inline-block">
                Technical Expertise
            </h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg max-w-3xl mx-auto">
                My specialized skill set is focused on delivering high-performance, maintainable Laravel applications using modern development practices.
            </p>
        </div>

        <!-- Skill categories tabs -->
        <div x-data="{ activeTab: 'backend' }" class="mb-12">
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                @foreach ($categories as $categoryKey => $category)
                    <button
                        @click="activeTab = '{{ $categoryKey }}'"
                        :class="{
                            'bg-{{ $category['color'] }}-100 dark:bg-{{ $category['color'] }}-900/30 text-{{ $category['color'] }}-800 dark:text-{{ $category['color'] }}-300 border-{{ $category['color'] }}-200 dark:border-{{ $category['color'] }}-800': activeTab === '{{ $categoryKey }}',
                            'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-{{ $category['color'] }}-50 dark:hover:bg-{{ $category['color'] }}-900/10': activeTab !== '{{ $categoryKey }}'
                        }"
                        class="px-5 py-3 rounded-full border transition-all duration-300 font-medium flex items-center gap-2 shadow-sm hover:shadow focus:outline-none text-sm sm:text-base"
                    >
                        <i class="{{ explode(' ', $category['icon'])[0] }} {{ explode(' ', $category['icon'])[1] }}"></i>
                        <span>{{ $category['name'] }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Skills cards for each category -->
            <div class="mt-6">
                @foreach ($categories as $categoryKey => $category)
                    <div x-show="activeTab === '{{ $categoryKey }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                        <!-- Category description -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-{{ $category['color'] }}-100 dark:bg-{{ $category['color'] }}-900/50 flex items-center justify-center">
                                    <i class="{{ explode(' ', $category['icon'])[0] }} {{ explode(' ', $category['icon'])[1] }}"></i>
                                </div>
                                <h3 class="text-xl font-bold text-{{ $category['color'] }}-600 dark:text-{{ $category['color'] }}-400">{{ $category['name'] }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 ml-13">{{ $category['description'] }}</p>
                        </div>

                        <!-- Skills grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($skills[$categoryKey] as $skill)
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-{{ $category['color'] }}-100 dark:bg-{{ $category['color'] }}-900/30 flex items-center justify-center flex-shrink-0">
                                            <i class="{{ $skill['icon'] }} text-{{ $category['color'] }}-500 dark:text-{{ $category['color'] }}-400 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $skill['name'] }}</h4>
                                                <div class="ml-2 flex gap-0.5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <div class="h-1.5 w-2.5 rounded-full {{ $i <= $skill['level'] ? "bg-$category[color]-500" : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $skill['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-16 text-center">
            <a href="/software-development" class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white font-medium rounded-full shadow hover:shadow-md transition-all duration-300 hover:bg-primary-600">
                <span>Explore My Tech Stack</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</section>