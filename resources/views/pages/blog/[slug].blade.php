@php
    use function Laravel\Folio\name;
    use function Laravel\Folio\{middleware};
    use App\Models\Post;

    name('blog.post');
    middleware(['web']);
@endphp

@volt
<?php
$post = Post::whereSlug($slug)->firstOrFail();
?>

<div>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <article class="prose dark:prose-invert max-w-none">
                        <h1>{{ $post->title }}</h1>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Published on {{ $post->published_at?->format('F j, Y') }}
                        </div>
                        <div class="mt-8">
                            {!! $post->content !!}
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
@endvolt