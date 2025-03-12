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
            // Get the first 200 characters of the body content
            $rawContent = strip_tags($post->body);

            // Clean the content - convert HTML entities and remove special characters
            $cleanContent = html_entity_decode($rawContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent); // Replace multiple spaces with single space
            $cleanContent = trim($cleanContent); // Remove leading/trailing whitespace

            // Generate excerpt (200 chars or less if content is shorter)
            $excerpt = Str::limit($cleanContent, 200, '...');

            // Set the excerpt
            $post->excerpt = $excerpt;
        }
    }
}
