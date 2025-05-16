<?php

use App\Models\Category;

use function Livewire\Volt\{with};

with(fn () => [
    'categories' => Category::withCount('posts')->orderBy('name')->get(),
]);

?>

<x-layouts.app
    title="Categories | Jordan Partridge"
    metaDescription="Browse content by category on Jordan Partridge's website. Laravel development, tutorials, and technical articles."
>
    <div class="relative flex flex-col w-full px-6 py-10 mx-auto lg:max-w-6xl sm:max-w-xl md:max-w-full sm:pb-16">
        <x-ui.marketing.breadcrumbs :crumbs="[
            ['href' => '/blog', 'text' => 'Blog'],
            ['text' => 'Categories']
        ]" class="mb-8" />

        <h1 class="text-3xl font-bold tracking-tight leading-tight dark:text-white font-heading md:text-4xl mb-8">
            Blog Categories
        </h1>

        @volt('categories.index')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($categories as $category)
                    <a href="/category/{{ $category->slug }}"
                       class="flex flex-col p-6 transition-transform transform hover:scale-105 border rounded-lg shadow-sm hover:shadow-md bg-white dark:bg-gray-800 dark:border-gray-700"
                       style="border-color: {{ $category->color }};">
                        <div class="w-12 h-12 mb-4 rounded-full flex items-center justify-center"
                             style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" style="color: {{ $category->color }};">
                            {{ $category->name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            {{ $category->posts_count }} {{ Str::plural('article', $category->posts_count) }}
                        </p>
                        <div class="mt-auto text-blue-600 dark:text-blue-400 font-medium flex items-center">
                            Browse Category
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($categories->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300">No categories found</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">There are no categories created yet.</p>
                    <a href="/blog" class="inline-flex items-center px-4 py-2 mt-6 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to Blog
                    </a>
                </div>
            @endif
        @endvolt
    </div>
</x-layouts.app>
