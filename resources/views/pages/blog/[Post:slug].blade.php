<?php
// Prepare SEO metadata based on post content
$metaTitle = $post->meta_title ?? $post->title . ' | Jordan Partridge';
$metaDescription = $post->meta_description ?? (strlen(strip_tags($post->body)) > 160 ? substr(strip_tags($post->body), 0, 157) . '...' : strip_tags($post->body));
$metaImage = $post->image ? (str_starts_with($post->image, 'https') || str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image)) : null;
$categoryNames = $post->categories->pluck('name')->toArray();

// Prepare JSON-LD for article
$jsonLd = [
    '@context'      => 'https://schema.org',
    '@type'         => 'BlogPosting',
    'headline'      => $post->title,
    'description'   => $metaDescription,
    'datePublished' => $post->created_at->toIso8601String(),
    'dateModified'  => $post->updated_at->toIso8601String(),
    'author'        => [
        '@type' => 'Person',
        'name'  => $post->user->name ?? 'Jordan Partridge'
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'Jordan Partridge',
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => asset('images/logo.png')
        ]
    ]
];

if ($metaImage) {
    $jsonLd['image'] = $metaImage;
}

if (!empty($categoryNames)) {
    $jsonLd['keywords'] = implode(', ', $categoryNames);
}
?>

<x-layouts.marketing
    :metaTitle="$metaTitle"
    :metaDescription="$metaDescription"
    :metaImage="$metaImage"
    :metaType="'article'"
    :metaUrl="url('/blog/' . $post->slug)"
    :metaJsonLd="$jsonLd"
>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <nav class="flex items-center justify-between mb-8">
                <x-ui.marketing.breadcrumbs :crumbs="[
                    ['href' => '/blog', 'text' => 'Blog'],
                    ['text' => $post->title]
                ]" class="text-gray-600 dark:text-gray-400" />

                <a href="/blog" class="group flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors duration-200">
                    <svg class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-sm font-medium">Back to Blog</span>
                </a>
            </nav>

            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                @if ($post->image)
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="@if (str_starts_with($post->image, 'https') || str_starts_with($post->image, 'http')){{ $post->image }}@else{{ asset('storage/' . $post->image) }}@endif" alt="{{ $post->title }}" class="object-cover w-full h-full">
                    </div>
                @endif

                <div class="px-6 py-8 sm:px-8">
                    <!-- Categories -->
                    @if ($post->categories->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-5">
                            @foreach ($post->categories as $category)
                                <a href="/category/{{ $category->slug }}"
                                   class="px-3 py-1 text-xs font-medium rounded-full transition-colors"
                                   style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white leading-tight mb-4">
                        {{ $post->title }}
                    </h1>

                    <div class="flex items-center mb-8 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            @if ($post->user && $post->user->avatar)
                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full mr-3">
                            @else
                                <div class="w-8 h-8 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full flex items-center justify-center mr-3">
                                    {{ substr($post->user->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                            <span>{{ $post->user->name ?? 'Jordan Partridge' }}</span>
                        </div>
                        <span class="mx-3">•</span>
                        <time datetime="{{ $post->created_at->toIso8601String() }}">
                            {{ $post->created_at->format('F j, Y') }}
                        </time>
                    </div>

                    <div class="prose prose-lg max-w-none dark:prose-invert prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-img:rounded-lg prose-headings:font-bold">
                        {!! $post->body !!}
                    </div>
                </div>
            </article>

            <!-- Author bio -->
            @if ($post->user)
            <div class="mt-10 bg-blue-50 dark:bg-gray-700 rounded-lg p-6">
                <div class="flex items-center">
                    @if ($post->user->avatar)
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="w-16 h-16 rounded-full mr-4">
                    @else
                        <div class="w-16 h-16 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 rounded-full flex items-center justify-center mr-4 text-xl font-bold">
                            {{ substr($post->user->name ?? 'J', 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</h3>
                        @if ($post->user->bio)
                            <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $post->user->bio }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-12 text-center">
                <a href="/blog" class="inline-flex items-center px-5 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    More Articles
                </a>
            </div>
        </div>
    </div>
</x-layouts.marketing>
