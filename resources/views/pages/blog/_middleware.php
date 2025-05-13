<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Add social media meta tags to the response
 */
function addSocialMetaTags($response, $post)
{
    // We need to modify the HTML content to add meta tags
    $content = $response->getContent();

    // Only add meta tags if we have a <head> section
    if (strpos($content, '</head>') !== false) {
        // Create meta tags
        $metaTags = <<<HTML
        <meta property="og:type" content="blog" />
        <meta property="og:title" content="{$post->title}" />
        <meta property="og:url" content="{$post->route()}" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{$post->title}" />
HTML;

        // Add image tag if available
        if ($post->image) {
            $metaTags .= <<<HTML
            <meta property="og:image" content="{$post->image}" />
            <meta name="twitter:image" content="{$post->image}" />
HTML;
        }

        // Add description if available
        if ($post->excerpt) {
            $metaTags .= <<<HTML
            <meta property="og:description" content="{$post->excerpt}" />
            <meta name="twitter:description" content="{$post->excerpt}" />
HTML;
        }

        // Insert meta tags before </head>
        $content = str_replace('</head>', $metaTags . "\n</head>", $content);
        $response->setContent($content);
    }

    return $response;
}

return [
    '*' => function (Request $request, Closure $next) {
        $startTime = microtime(true);

        $response = $next($request);

        // Add security headers
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('X-Content-Type-Options', 'nosniff');

        // Calculate response time
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2);
        $response->header('X-Response-Time-Ms', $responseTime);

        // Default cache time for blog index (1 hour)
        $cacheTime = 3600;

        // Get current route parameters
        $routeParameters = Route::current()->parameters();

        // If this is a single post page (has slug parameter)
        if (isset($routeParameters['Post:slug'])) {
            // Individual posts have 4 hour cache
            $cacheTime = 14400;

            // Get the post
            $slug = $routeParameters['Post:slug'];
            $post = Post::where('slug', $slug)->first();

            // Add social meta tags for individual posts
            if ($post) {
                $response = addSocialMetaTags($response, $post);
            }
        }

        // Set cache headers
        $response->header('Cache-Control', "public, max-age={$cacheTime}");

        return $response;
    },
];
