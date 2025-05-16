<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Ride;
use App\Services\RideMetricService;

return function (Request $request, Closure $next) {
    // Preload common Strava data if we're on the bike index page
    // This can help with performance by loading data once that might be used in multiple components
    if ($request->path() === 'bike') {
        $rideMetrics = app(RideMetricService::class)->getBasicMetrics();
        
        // Share the metrics with all views in this section
        view()->share('rideMetrics', $rideMetrics);
    }
    
    // For specific ride pages, preload the ride data
    $routeParams = $request->route()->parameters();
    if (isset($routeParams['id'])) {
        $ride = Cache::remember('ride_' . $routeParams['id'], 3600, function () use ($routeParams) {
            return Ride::find($routeParams['id']);
        });
        
        // Share the ride with the view
        if ($ride) {
            view()->share('ride', $ride);
        }
    }
    
    // Get response
    $response = $next($request);
    
    // Add bike-specific meta tags
    $content = $response->getContent();
    if (!str_contains($content, '<meta property="og:type"')) {
        $metaTags = '    <meta property="og:type" content="activity" />' . PHP_EOL;
        
        if (isset($routeParams['id']) && $ride) {
            // Specific ride page
            $metaTags .= '    <meta property="og:title" content="' . htmlspecialchars($ride->name) . '" />' . PHP_EOL;
            $metaTags .= '    <meta property="og:description" content="' . htmlspecialchars("A {$ride->distance_formatted} ride with {$ride->elevation_gain_formatted} of elevation gain.") . '" />' . PHP_EOL;
            
            if ($ride->map_image) {
                $metaTags .= '    <meta property="og:image" content="' . $ride->map_image . '" />' . PHP_EOL;
            }
        } else {
            // Bike index page
            $metaTags .= '    <meta property="og:title" content="Bike Rides - Jordan Partridge" />' . PHP_EOL;
            $metaTags .= '    <meta property="og:description" content="Tracking my cycling journey and adventures." />' . PHP_EOL;
        }
        
        $content = str_replace('</head>', $metaTags . '</head>', $content);
        $response->setContent($content);
    }
    
    // Set appropriate cache headers for bike pages
    // Ride data doesn't change frequently once synced
    $response->headers->set('Cache-Control', 'public, max-age=3600');
    
    // Track bike page views specifically
    activity('bike_view')
        ->withProperties([
            'url' => $request->fullUrl(),
            'ride_id' => $routeParams['id'] ?? null,
        ])
        ->log('Bike page viewed');
    
    return $response;
};

