<x-layouts.marketing>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <nav class="flex items-center justify-between mb-12">
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
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white leading-tight mb-4">
                        {{ $post->title }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-white mb-8">Posted on {{ $post->created_at->format('F j, Y') }}</p>

                    <div class="prose prose-lg max-w-none dark:prose-invert dark:text-white">
                        {!! $post->body !!}
                    </div>
                </div>
            </article>

            <div class="mt-12 text-center">
                <a href="/blog" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    More Articles
                </a>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .prose {
            --tw-prose-body: theme('colors.gray.700');
            --tw-prose-headings: theme('colors.gray.900');
            --tw-prose-links: theme('colors.blue.600');
            --tw-prose-links-hover: theme('colors.blue.700');
            --tw-prose-underline: theme('colors.blue.500 / 0.2');
            --tw-prose-underline-hover: theme('colors.blue.500');
            --tw-prose-bold: theme('colors.gray.900');
            --tw-prose-counters: theme('colors.gray.500');
            --tw-prose-bullets: theme('colors.gray.300');
            --tw-prose-hr: theme('colors.gray.200');
            --tw-prose-quote-borders: theme('colors.gray.200');
            --tw-prose-captions: theme('colors.gray.500');
            --tw-prose-code: theme('colors.gray.900');
            --tw-prose-pre-code: theme('colors.gray.200');
            --tw-prose-pre-bg: theme('colors.gray.800');
            --tw-prose-pre-border: theme('colors.transparent');
            --tw-prose-th-borders: theme('colors.gray.300');
            --tw-prose-td-borders: theme('colors.gray.200');
        }

        .dark .prose {
            --tw-prose-body: theme('colors.gray-300');
            --tw-prose-headings: theme('colors.white');
            --tw-prose-links: theme('colors.blue-400');
            --tw-prose-links-hover: theme('colors.blue-300');
            --tw-prose-underline: theme('colors.blue-400 / 0.3');
            --tw-prose-underline-hover: theme('colors.blue-400');
            --tw-prose-bold: theme('colors.white');
            --tw-prose-counters: theme('colors.gray-400');
            --tw-prose-bullets: theme('colors.gray-600');
            --tw-prose-hr: theme('colors.gray-700');
            --tw-prose-quote-borders: theme('colors.gray-600');
            --tw-prose-captions: theme('colors.gray-400');
            --tw-prose-code: theme('colors.white');
            --tw-prose-pre-code: theme('colors.gray-300');
            --tw-prose-pre-bg: theme('colors.gray-900');
            --tw-prose-th-borders: theme('colors.gray-600');
            --tw-prose-td-borders: theme('colors.gray-700');
        }
    </style>
</x-layouts.marketing>
