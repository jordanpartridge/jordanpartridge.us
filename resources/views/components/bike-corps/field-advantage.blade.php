@props([
    'icon' => '',
    'title' => '',
    'description' => '',
])

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md camo-border">
    <div class="flex items-center mb-4">
        <span class="text-3xl mr-3">{{ $icon }}</span>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white military-font">{{ $title }}</h3>
    </div>
    <p class="text-gray-600 dark:text-gray-300">
        {{ $description }}
    </p>
</div>