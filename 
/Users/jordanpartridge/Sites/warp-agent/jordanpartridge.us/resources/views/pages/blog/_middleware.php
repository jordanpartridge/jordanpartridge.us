<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;

return function (Request $request, Closure $next) {
    // Get response
    $response = $next($request);
    
    // Add blog-specific meta tags
    $content = $response->getContent();
    if (!str_contains($content, '<meta property="og:type"')) {
        $metaTags = '    <meta property="og:type" content="blog" />' . PHP_EOL;
        $metaTags .= '    <meta name="twitter:card" content="summary_large_image" />' . PHP_EOL;
        
        // If we're on a specific blog post, add more specific meta tags
        $routeParams = $request->route()->parameters();
        if (isset($routeParams['slug'])) {
            $post = Post::where('slug', $routeParams['slug'])->first();
            
            if ($post) {
                $metaTags .= '    <meta property="og:title" content="' . htmlspecialchars($post->title) . '" />' . PHP_EOL;
                $metaTags .= '    <meta property="og:description" content="' . htmlspecialchars($post->excerpt ?? '') . '" />' . PHP_EOL;
                
                if ($post->featured_image) {
                    $metaTags .= '    <meta property="og:image" content="' . $post->featured_image . '" />' . PHP_EOL;
                }
            }
        } else {
            // Blog index page
            $metaTags .= '    <meta property="og:title" content="Blog - Jordan Partridge" />' . PHP_EOL;
            $metaTags .= '    <meta property="og:description" content="Thoughts on software development, technology, and more." />' . PHP_EOL;
        }
        
        $content = str_replace('</head>', $metaTags . '</head>', $content);
        $response->setContent($content);
    }
    
    // Adjust cache headers for blog content
    // Blog index pages cache for shorter time than individual posts
    if (!isset($routeParams['slug'])) {
        // Blog index - cache for 1 hour
        $response->headers->set('Cache-Control', 'public, max-age=3600');
    } else {
        // Individual post - cache longer (4 hours) since they change less frequently
        $response->headers->set('Cache-Control', 'public, max-age=14400');
    }
    
    // Track blog-specific analytics
    activity('blog_view')
        ->withProperties([
            'url' => $request->fullUrl(),
            'post_slug' => $routeParams['slug'] ?? 'index',
        ])
        ->log('Blog page viewed');
    
    return $response;
};

