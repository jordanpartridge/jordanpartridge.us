<?php

use App\Models\Category;
use App\Models\Post;

use function Livewire\Volt\{with, state, mount, usesPagination};

usesPagination();

state([
    'numResults'   => 6,
    'results'      => null,
    'total'        => '',
    'finished'     => false,
    'route_prefix' => 'blog',
    'sortOrder'    => 'desc',
    'category'     => null,
]);

mount(function (Category $category) {
    $this->category = $category;
    $this->results = $this->numResults;
    $this->total = Post::whereHas('categories', function ($query) use ($category) {
        $query->where('categories.id', $category->id);
    })->count();
});

$loadMore = function () {
    $this->results += $this->numResults;
    if ($this->results >= $this->total) {
        $this->finished = true;
    }
};

with(fn () => [
    'posts' => Post::query()
                ->where('status', 'published')
                ->whereHas('categories', function ($query) {
                    $query->where('categories.id', $this->category->id);
                })
                ->orderBy('created_at', $this->sortOrder)
                ->with(['user', 'categories'])
                ->paginate($this->results)
]);

?>

<x-layouts.marketing>
    <div class="relative flex flex-col w-full px-6 py-10 mx-auto lg:max-w-6xl sm:max-w-xl md:max-w-full sm:pb-16">
        <!-- Category Header -->
        <div class="mb-10 text-center">
            <x-ui.marketing.breadcrumbs :crumbs="[
                ['href' => '/blog', 'text' => 'Blog'],
                ['text' => $category->name]
            ]" class="mb-5 justify-center" />

            <span class="inline-block px-5 py-2 mb-4 text-sm font-medium rounded-full"
                  style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                {{ $category->name }}
            </span>

            <h1 class="text-3xl font-bold tracking-tight leading-tight dark:text-white font-heading md:text-4xl">
                {{ $category->name }} Articles
            </h1>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                Explore all posts in the {{ $category->name }} category
            </p>
        </div>

        @volt('category.show')
            <div class="relative w-full space-y-10">
                @if ($posts->count() > 0)
                    @foreach ($posts as $post)
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
                                        @foreach ($post->categories as $cat)
                                            <a href="/category/{{ $cat->slug }}"
                                               class="px-3 py-1 text-xs font-medium rounded-full transition-colors"
                                               style="background-color: {{ $cat->color }}20; color: {{ $cat->color }};">
                                                {{ $cat->name }}
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
                    @endforeach

                    <div class="flex items-center justify-center w-full pt-5">
                        @if (!$finished)
                            <button wire:click="loadMore" class="inline-flex items-center justify-center px-5 py-2.5 font-medium bg-gray-200 text-gray-700 hover:text-gray-900 hover:bg-gray-300 dark:text-gray-100 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700 border border-transparent rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-700">
                                <span>Load More Posts</span>
                            </button>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400">That's all the posts in this category!</p>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300">No posts found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">There are no posts in this category yet.</p>
                        <a href="/blog" class="inline-flex items-center px-4 py-2 mt-6 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to all posts
                        </a>
                    </div>
                @endif
            </div>
        @endvolt
    </div>
</x-layouts.marketing>
