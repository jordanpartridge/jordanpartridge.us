@props([
    'name',
    'level' => 0, // 1-5 (1: Beginner, 5: Expert)
    'category' => null, // frontend, backend, devops, etc.
    'icon' => null, // FontAwesome icon class
])

@php
    $colorMap = [
        'frontend' => [
            'bg' => 'from-teal-500 to-teal-600',
            'light' => 'bg-teal-100 text-teal-800 border-teal-200',
            'dark' => 'dark:bg-teal-900/30 dark:text-teal-300 dark:border-teal-800',
        ],
        'backend' => [
            'bg' => 'from-blue-500 to-blue-600',
            'light' => 'bg-blue-100 text-blue-800 border-blue-200',
            'dark' => 'dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
        ],
        'devops' => [
            'bg' => 'from-purple-500 to-purple-600',
            'light' => 'bg-purple-100 text-purple-800 border-purple-200',
            'dark' => 'dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
        ],
        'database' => [
            'bg' => 'from-indigo-500 to-indigo-600',
            'light' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'dark' => 'dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800',
        ],
        'design' => [
            'bg' => 'from-pink-500 to-pink-600',
            'light' => 'bg-pink-100 text-pink-800 border-pink-200',
            'dark' => 'dark:bg-pink-900/30 dark:text-pink-300 dark:border-pink-800',
        ],
        'testing' => [
            'bg' => 'from-green-500 to-green-600',
            'light' => 'bg-green-100 text-green-800 border-green-200',
            'dark' => 'dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
        ],
        'default' => [
            'bg' => 'from-gray-500 to-gray-600',
            'light' => 'bg-gray-100 text-gray-800 border-gray-200',
            'dark' => 'dark:bg-gray-900/30 dark:text-gray-300 dark:border-gray-700',
        ],
    ];

    $colors = $colorMap[$category ?? 'default'];
@endphp

<div {{ $attributes->merge(['class' => "group relative rounded-lg px-4 py-3 transition-all duration-300 transform hover:scale-105 border {$colors['light']} {$colors['dark']}"]) }}>
    <div class="flex items-center gap-2">
        @if ($icon)
            <i class="{{ $icon }} mr-2"></i>
        @endif

        <span class="font-medium">{{ $name }}</span>

        @if ($level > 0)
            <div class="ml-auto flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="h-1.5 w-3 rounded-full {{ $i <= $level ? "bg-gradient-to-r {$colors['bg']}" : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                @endfor
            </div>
        @endif
    </div>

    <div class="absolute inset-0 bg-gradient-to-r {{ $colors['bg'] }} rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
</div>