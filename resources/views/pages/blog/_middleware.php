<?php

// Blog module middleware
use App\Models\Post;
use Illuminate\Support\Facades\Analytics;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

return function (\Illuminate\Http\Request $request, \Closure $next) {
    // Cache key for blog-related pages
    $cacheKey = 'blog.page_' . md5($request->fullUrl());

    // Set cache headers for blog pages
    $response = $next($request);
    $response->header('Cache-Control', 'public, max-age=300');

    // Add analytics tracking for blog pages
    try {
        Analytics::trackView('/blog' . ($request->path() !== 'blog' ? '/' . $request->path() : ''));
    } catch (\Exception $e) {
        report($e);
    }

    // Get route parameters for blog page
    $routeParams = Route::current()->parameters();

    // If we're on a specific post page, add appropriate Open Graph tags
    if (isset($routeParams['Post'])) {
        $post = $routeParams['Post'];

        // Set Open Graph meta tags for the post
        $response->header('x-og-title', $post->title);
        $response->header('x-og-description', $post->excerpt ?? substr(strip_tags($post->body), 0, 160));

        // Add validation for post image URL before setting the meta tag
        if ($post->image && (str_starts_with($post->image, 'http') || file_exists(public_path('storage/' . $post->image)))) {
            $response->header('x-og-image', str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image));
        } else {
            // Fallback to a default image if post image is missing or invalid
            $response->header('x-og-image', asset('img/hero.gif'));
        }
    } else {
        // Default Open Graph tags for blog section
        $response->header('x-og-title', "Jordan's Blog");
        $response->header('x-og-description', 'Thoughts on software, cycling, and life in general');
        $response->header('x-og-image', asset('img/hero.gif'));
    }

    return $response;
};
