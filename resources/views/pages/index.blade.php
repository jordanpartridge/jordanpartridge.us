@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('pages.index');
    middleware(['web']);
@endphp

@volt
<?php
$featuredPosts = collect([]); // This should come from your Posts model later
?>

<div>
    <x-layouts.marketing>
        <!-- Hero Section -->
        <div class="relative isolate">
            <svg class="absolute inset-x-0 top-0 -z-10 h-[64rem] w-full stroke-gray-200 dark:stroke-gray-800 [mask-image:radial-gradient(32rem_32rem_at_center,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="grid-pattern" width="25" height="25" patternUnits="userSpaceOnUse">
                        <path d="M.5 25V.5H25" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
            <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-6xl">
                        Full Stack Software Engineer
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        I'm Jordan Partridge, building modern web applications and solutions. Specializing in Laravel, Vue.js, and developer experience.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="/software-development" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            View Projects
                        </a>
                        <a href="/learn" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            Learn More <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Posts Section -->
        <div class="bg-white dark:bg-gray-900 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:max-w-4xl">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">Latest Posts</h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        Learn about software development, cycling, and more.
                    </p>
                    <div class="mt-16 space-y-20 lg:mt-20 lg:space-y-20">
                        @forelse ($featuredPosts as $post)
                            <article class="relative isolate flex flex-col gap-8 lg:flex-row">
                                <div class="lg:w-1/2 lg:flex-auto">
                                    <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                        <a href="/blog/{{ $post->slug }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-5 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ $post->description }}</p>
                                </div>
                            </article>
                        @empty
                            <p class="text-gray-600 dark:text-gray-400">No posts available yet. Check back soon!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.marketing>
</div>
@endvolt