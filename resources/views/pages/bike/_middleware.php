<?php

use Illuminate\Http\Request;

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

        // Add cache headers for bike pages (1 hour)
        $response->header('Cache-Control', 'public, max-age=3600');

        return $response;
    },
];
