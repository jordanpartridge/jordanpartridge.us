@props([
    'title',
    'description',
    'image' => null,
    'imageAlt' => '',
    'link' => null,
    'tags' => [],
    'github' => null,
    'demo' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl group']) }}>
    <!-- Top gradient bar to add visual interest -->
    <div class="h-1.5 w-full bg-gradient-to-r from-primary-500 to-secondary-500"></div>

    <!-- Project image with overlay on hover -->
    @if ($image)
    <div class="relative overflow-hidden aspect-video">
        <img
            src="{{ $image }}"
            alt="{{ $imageAlt }}"
            class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-start p-6">
            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <h3 class="text-white text-xl font-bold">{{ $title }}</h3>
                <p class="text-white/80 text-sm mt-1">{{ $description }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Content section -->
    <div class="p-6">
        @if (!$image)
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ $description }}</p>
        @endif

        <!-- Tags -->
        @if (count($tags) > 0)
        <div class="flex flex-wrap gap-2 mt-4">
            @foreach ($tags as $tag)
                <span class="px-2.5 py-0.5 text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 rounded-full">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
        @endif

        <!-- Action links -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex gap-3">
                @if ($github)
                <a href="{{ $github }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                    <i class="fab fa-github"></i>
                    <span class="text-sm">Code</span>
                </a>
                @endif

                @if ($demo)
                <a href="{{ $demo }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="text-sm">Demo</span>
                </a>
                @endif
            </div>

            @if ($link)
            <a href="{{ $link }}" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-200">
                <span>Details</span>
                <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            @endif
        </div>
    </div>
</div>