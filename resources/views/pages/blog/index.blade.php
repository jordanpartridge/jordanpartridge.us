<?php

use App\Models\Post;
use App\Models\Category;

use function Livewire\Volt\{with, state, mount, usesPagination};

usesPagination();

state([
    'numResults'   => 6,
    'results'      => null,
    'total'        => '',
    'finished'     => false,
    'route_prefix' => 'blog',
    'sortOrder'    => 'desc',
    'searchQuery'  => '',
    'showSearch'   => false
]);

mount(function () {
    $this->results = $this->numResults;
    $this->total = Post::where('status', 'published')->count();
});

$loadMore = function () {
    $this->results += $this->numResults;
    if ($this->results >= $this->total) {
        $this->finished = true;
    }
};

$toggleSearch = function () {
    $this->showSearch = !$this->showSearch;
    if (!$this->showSearch) {
        $this->searchQuery = '';
    }
};

$search = function () {
    // Search implementation would go here
    // For now, we'll just reset the search UI
    $this->searchQuery = '';
    $this->showSearch = false;
};

with(fn () => [
    'posts' => Post::query()
                ->where('status', 'published')
                ->orderBy('created_at', $this->sortOrder)
                ->with(['user', 'categories'])
                ->paginate($this->results),
    'categories' => Category::withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(5)
                ->get(),
    'featuredPost' => Post::where('status', 'published')
                ->where('featured', true)
                ->with(['user', 'categories'])
                ->latest()
                ->first(),
    'metaTitle'       => 'Blog | Jordan Partridge',
    'metaDescription' => 'Explore articles on software development, cycling, and technology by Jordan Partridge',
    'metaType'        => 'blog',
    'metaJsonLd'      => [
        '@context'    => 'https://schema.org',
        '@type'       => 'Blog',
        'name'        => 'Jordan Partridge\'s Blog',
        'description' => 'Thoughts on software, cycling, and life in general',
        'url'         => url('/blog')
    ]
]);


?>

<x-layouts.marketing>

    <x-ui.marketing.breadcrumbs :crumbs="[ ['text' => 'Blog'] ]" />

    @volt('blog.index')
        <div class="relative flex flex-col w-full px-6 py-10 mx-auto lg:max-w-6xl sm:max-w-xl md:max-w-full sm:pb-16">
            <!-- Blog Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tighter leading-tighter dark:text-white font-heading md:text-3xl">Jordan's Blog</h1>
                    <p class="w-full mt-2 text-base font-medium text-neutral-400 dark:text-slate-400 md:mt-2">Thoughts on software, cycling, and life in general</p>
                </div>

                <div class="mt-4 md:mt-0 flex items-center">
                    <!-- Search Toggle Button -->
                    <button
                        wire:click="toggleSearch"
                        class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>

                    <!-- Categories Link -->
                    <a href="/categories" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Categories
                    </a>
                </div>
            </div>

            <!-- Search Bar (conditionally shown) -->
            @if ($showSearch)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-8 animate-fade-in">
                    <div class="flex">
                        <input
                            type="text"
                            wire:model="searchQuery"
                            placeholder="Search articles..."
                            class="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-l-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                        <button
                            wire:click="search"
                            class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Search
                        </button>
                    </div>
                </div>
            @endif

            <!-- Featured Categories -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Browse by Category</h2>
                    <a href="/categories" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <a href="/category/{{ $category->slug }}"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-colors"
                            style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                            {{ $category->name }} ({{ $category->posts_count }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Featured Post (if exists) -->
            @if ($featuredPost)
                <div class="mb-10">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-full mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Featured
                        </span>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Featured Article</h2>
                    </div>

                    <a href="/{{ $this->route_prefix }}/{{ $featuredPost->slug }}" class="block overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-md transition-transform hover:shadow-lg">
                        <div class="md:flex">
                            <div class="md:w-2/5 h-64 md:h-auto">
                                @if ($featuredPost->image)
                                    <img src="@if (str_starts_with($featuredPost->image, 'https') || str_starts_with($featuredPost->image, 'http')){{ storage_path($featuredPost->image) }}@else{{ asset('storage/' . $featuredPost->image) }}@endif"
                                        class="w-full h-full object-cover"
                                        alt="{{ $featuredPost->title }}" />
                                @else
                                    <div class="flex items-center justify-center w-full h-full bg-gray-200 dark:bg-gray-700">
                                        <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 md:w-3/5">
                                <!-- Categories -->
                                @if ($featuredPost->categories->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach ($featuredPost->categories as $category)
                                            <span class="px-3 py-1 text-xs font-medium rounded-full"
                                                  style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $featuredPost->title }}</h2>

                                <p class="text-gray-700 dark:text-gray-300 mb-4">
                                    {!! $featuredPost->excerpt ?? substr(strip_tags($featuredPost->body), 0, 160) . (strlen(strip_tags($featuredPost->body)) > 160 ? '...' : '') !!}
                                </p>

                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $featuredPost->created_at->toIso8601String() }}">
                                        {{ $featuredPost->created_at->format('F j, Y') }}
                                    </time>
                                    <span class="mx-2">·</span>
                                    <span>{{ $featuredPost->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <!-- Recent Posts Heading -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Articles</h2>

                <!-- Sort Order Toggle -->
                <button
                    wire:click="$set('sortOrder', '{{ $sortOrder === 'desc' ? 'asc' : 'desc' }}')"
                    class="flex items-center px-3 py-1.5 text-xs font-medium bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                    <span>{{ $sortOrder === 'desc' ? 'Newest First' : 'Oldest First' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                </button>
            </div>

            <!-- Posts List -->
            <div class="relative w-full space-y-10">
                @foreach ($posts as $post)
                    @if (!$featuredPost || $post->id !== $featuredPost->id)
                        <article class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                            <a href="/{{ $this->route_prefix }}/{{ $post->slug }}" class="block w-full md:w-5/12 h-56 md:h-auto">
                                @if ($post->image)
                                    <img src="@if (str_starts_with($post->image, 'https') || str_starts_with($post->image, 'http')){{ storage_path($post->image) }}@else{{ asset('storage/' . $post->image) }}@endif"
                                         class="relative object-cover w-full h-full bg-gray-200 rounded-lg shadow-lg md:aspect-video dark:bg-slate-700"
                                         alt="{{ $post->title }}" />
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-gray-500 bg-gray-200 rounded-lg dark:bg-gray-800 md:aspect-video">
                                        <svg class="w-12 h-12 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            <div class="w-full md:w-7/12">
                                <!-- Categories -->
                                @if ($post->categories->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach ($post->categories as $category)
                                            <a href="/category/{{ $category->slug }}"
                                               class="px-3 py-1 text-xs font-medium rounded-full transition-colors"
                                               style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Title -->
                                <h2 class="mb-2 text-xl font-medium leading-tight transition-colors hover:text-blue-600 dark:hover:text-blue-400 dark:text-slate-300 font-heading sm:text-2xl">
                                    <a href="{{ $this->route_prefix }}/{{ $post->slug }}">{{ $post->title }}</a>
                                </h2>

                                <!-- Metadata -->
                                <div class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $post->created_at->toIso8601String() }}">
                                        {{ $post->created_at->format('F j, Y') }}
                                    </time>
                                    <span class="mx-2">·</span>
                                    <span>{{ $post->user->name }}</span>
                                </div>

                                <!-- Excerpt -->
                                <p class="flex-grow text-lg font-light text-gray-700 dark:text-gray-300">
                                    {!! $post->excerpt ?? substr(strip_tags($post->body), 0, 200) . (strlen(strip_tags($post->body)) > 200 ? '...' : '') !!}
                                </p>

                                <!-- Read more link -->
                                <div class="mt-4">
                                    <a href="{{ $this->route_prefix }}/{{ $post->slug }}"
                                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Read more
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach

                <div class="flex items-center justify-center w-full pt-5">
                    @if (!$finished)
                        <button wire:click="loadMore" class="inline-flex items-center justify-center px-5 py-2.5 font-medium bg-gray-200 text-gray-700 hover:text-gray-900 hover:bg-gray-300 dark:text-gray-100 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700 border border-transparent rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-700">
                            <span>Load More Posts</span>
                        </button>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">That's all the posts for now!</p>
                    @endif
                </div>
            </div>

        </div>
    @endvolt
</x-layouts.marketing>
