<div class="flex items-center space-x-3">
    <!-- Twitter Share -->
    <a
        href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($title) }}&hashtags={{ $hashtags }}"
        target="_blank"
        class="flex items-center text-gray-600 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
        wire:click="trackShare('twitter')"
        rel="nofollow noopener"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
        @if ($showCount)
        <span class="ml-1 text-xs">{{ $twitterCount }}</span>
        @endif
    </a>

    <!-- LinkedIn Share -->
    <a
        href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}"
        target="_blank"
        class="flex items-center text-gray-600 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-600 transition-colors"
        wire:click="trackShare('linkedin')"
        rel="nofollow noopener"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM8 19H5v-9h3zM6.5 8.25A1.75 1.75 0 118.3 6.5a1.78 1.78 0 01-1.8 1.75zM19 19h-3v-4.74c0-1.42-.6-1.93-1.38-1.93A1.74 1.74 0 0013 14.19a.66.66 0 000 .14V19h-3v-9h2.9v1.3a3.11 3.11 0 012.7-1.4c1.55 0 3.36.86 3.36 3.66z"></path>
        </svg>
        @if ($showCount)
        <span class="ml-1 text-xs">{{ $linkedinCount }}</span>
        @endif
    </a>

    <!-- Facebook Share -->
    <a
        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
        target="_blank"
        class="flex items-center text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500 transition-colors"
        wire:click="trackShare('facebook')"
        rel="nofollow noopener"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 011-1h3v-4h-3a5 5 0 00-5 5v2.01h-2l-.396 3.98h2.396v8.01z" />
        </svg>
        @if ($showCount)
        <span class="ml-1 text-xs">{{ $facebookCount }}</span>
        @endif
    </a>
</div>
