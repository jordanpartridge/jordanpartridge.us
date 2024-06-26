<!-- resources/views/components/ui/nav-link.blade.php -->
@props(['active' => false])

<a {{ $attributes->merge(['class' => 'relative px-2 py-1 text-sm font-medium transition-colors duration-200 hover:text-indigo-600 dark:hover:text-indigo-400' .
    ($active ? ' text-indigo-600 dark:text-indigo-400' : ' text-gray-600 dark:text-gray-300')]) }}>
    {{ $slot }}
    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400 transform origin-left transition-transform duration-300 ease-out {{ $active ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100"></span>
</a>
