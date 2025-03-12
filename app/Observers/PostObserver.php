<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "saving" event.
     */
    public function saving(Post $post): void
    {
        // Only auto-generate excerpt if it's empty
        if (empty($post->excerpt)) {
            // Add spaces before block-level HTML tags to prevent words from being squished together
            $spacedContent = preg_replace('/<\/(p|div|h[1-6]|ul|ol|li|blockquote)><(p|div|h[1-6]|ul|ol|li|blockquote)>/i', ' ', $post->body);

            // Get the content without HTML tags
            $rawContent = strip_tags($spacedContent);

            // Clean the content - convert HTML entities and remove special characters
            $cleanContent = html_entity_decode($rawContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Replace multiple spaces with single space
            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);

            // Remove leading/trailing whitespace
            $cleanContent = trim($cleanContent);

            // Escape HTML-like characters for safety
            $cleanContent = htmlspecialchars($cleanContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Generate excerpt (200 chars or less if content is shorter)
            $excerpt = Str::limit($cleanContent, 200, '...');

            // Set the excerpt
            $post->excerpt = $excerpt;
        }
    }
}
