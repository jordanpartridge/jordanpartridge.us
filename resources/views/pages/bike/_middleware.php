<?php

// Bike module middleware for Laravel 12
use App\Services\RideMetricService;
use Illuminate\Support\Facades\Analytics;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return function (Request $request) {
    // Set cache headers for bike pages
    $response = next($request);
    $response->header('Cache-Control', 'public, max-age=300');

    // Add analytics tracking for bike pages
    try {
        Analytics::trackView('/bike' . ($request->path() !== 'bike' ? '/' . $request->path() : ''));
    } catch (\Exception $e) {
        report($e);
    }

    // Get route parameters for bike page
    $routeParams = Route::current()->parameters();

    // If we're on a specific ride page, add appropriate Open Graph tags
    if (isset($routeParams['Ride'])) {
        $ride = $routeParams['Ride'];

        // Set Open Graph meta tags for the page
        $response->header('x-og-title', 'Bike Ride: ' . $ride->name);
        $response->header('x-og-description', 'Distance: ' . $ride->distance_imperial . ' miles, Duration: ' . $ride->moving_time_formatted);

        // Add validation for map_image URL before setting the meta tag
        if ($ride->map_image && filter_var($ride->map_image, FILTER_VALIDATE_URL)) {
            $response->header('x-og-image', $ride->map_image);
        } else {
            // Fallback to a default image if map_image is missing or invalid
            $response->header('x-og-image', asset('img/bike-joy.jpg'));
        }
    } else {
        // Default Open Graph tags for bike section
        $response->header('x-og-title', 'Bike Joy - Fat Bike Division');
        $response->header('x-og-description', 'Exploring trails and conquering terrain with fat tires');
        $response->header('x-og-image', asset('img/bike-joy.jpg'));
    }

    // Preload metrics for bike dashboard
    if ($request->path() === 'bike') {
        app(RideMetricService::class)->preloadMetrics();
    }

    return $response;
};
