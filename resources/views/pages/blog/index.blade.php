@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};

    name('blog.index');
    middleware(['web']);
@endphp

@volt
<?php
$posts = collect([]); // This should come from your Posts model later
?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Blog Posts</h1>

                    <div class="space-y-10">
                        @forelse ($posts as $post)
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
    </x-app-layout>
</div>
@endvolt