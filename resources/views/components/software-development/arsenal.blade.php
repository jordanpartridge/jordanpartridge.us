<div class="mb-20">
    <h2 class="text-3xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 dark:from-blue-400 dark:to-green-400 mb-10">
        Arsenal</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach([
            ['Laravel', 95, 'https://laravel.com/img/logomark.min.svg', 'Expert-level proficiency in Laravel, capable of architecting complex applications'],
            ['Vue.js', 85, 'https://vuejs.org/images/logo.png', 'Advanced knowledge in Vue.js, building interactive and responsive UIs'],
            ['Tailwind CSS', 90, '/img/tailwind.png', 'Mastery in rapid, utility-first CSS development with Tailwind'],
            ['Livewire', 80, 'https://raw.githubusercontent.com/livewire/livewire/master/art/logo.svg', 'Strong capability in building dynamic interfaces with Livewire'],
            ['Laravel Folio', 75, 'https://laravel.com/img/logomark.min.svg', 'Proficient in utilizing Laravel Folio for efficient routing and organization']
        ] as [$tech, $proficiency, $logoUrl, $description])
            <div
                class="bg-white dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-30 rounded-lg p-6 shadow-2xl backdrop-filter backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:shadow-blue-500/50 dark:hover:shadow-purple-500/50 transition duration-300">
                <div class="flex items-center mb-4">
                    <img src="{{ $logoUrl }}" alt="{{ $tech }}" class="w-10 h-10 mr-4">
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $tech }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-2">
                    <div
                        class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full transition-all duration-1000"
                        style="width: 0%" x-data
                        x-init="setTimeout(() => $el.style.width = '{{ $proficiency }}%', 300)"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
            </div>
        @endforeach
    </div>
</div>
