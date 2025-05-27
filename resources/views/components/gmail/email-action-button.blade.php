@props([
    'action' => '',
    'variant' => 'primary',
    'icon' => null,
    'label' => '',
    'size' => 'default',
    'onclick' => null,
    'wireClick' => null,
    'href' => null,
    'target' => null
])

@php
    $baseClasses = 'inline-flex items-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg';

    $variants = [
        'primary' => 'text-blue-600 hover:text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-800/50 focus:ring-blue-500 shadow-sm hover:shadow-md border dark:border-blue-800',
        'secondary' => 'text-gray-600 hover:text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500 shadow-sm hover:shadow-md border dark:border-gray-700',
        'success' => 'text-green-600 hover:text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-800/50 focus:ring-green-500 shadow-sm hover:shadow-md border dark:border-green-800',
        'warning' => 'text-yellow-600 hover:text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-800/50 focus:ring-yellow-500 shadow-sm hover:shadow-md border dark:border-yellow-800',
        'danger' => 'text-red-600 hover:text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-800/50 focus:ring-red-500 shadow-sm hover:shadow-md border dark:border-red-800',
        'github' => 'text-gray-900 hover:text-black dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-gray-500 shadow-sm hover:shadow-md border dark:border-gray-700',
        'laravel' => 'text-red-600 hover:text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-800/50 focus:ring-red-500 shadow-sm hover:shadow-md border dark:border-red-800'
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'default' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];

    $iconSizes = [
        'sm' => 'w-3 h-3',
        'default' => 'w-4 h-4',
        'lg' => 'w-5 h-5'
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['default']);
    $iconClass = $iconSizes[$size] ?? $iconSizes['default'];
@endphp

@if ($href)
    <a href="{{ $href }}"
       @if ($target) target="{{ $target }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-dynamic-component :component="'gmail.icons.' . $icon" :class="$iconClass . ' mr-2'" />
        @endif
        {{ $label }}
        {{ $slot }}
    </a>
@else
    <button @if ($wireClick) wire:click="{{ $wireClick }}" @endif
            @if ($onclick) onclick="{{ $onclick }}" @endif
            {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-dynamic-component :component="'gmail.icons.' . $icon" :class="$iconClass . ' mr-2'" />
        @endif
        {{ $label }}
        {{ $slot }}
    </button>
@endif