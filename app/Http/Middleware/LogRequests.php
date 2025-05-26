<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip logging for Gmail OAuth callback to prevent database overflow from long OAuth URLs
        if ($request->is('gmail/auth/callback')) {
            return $response;
        }

        // Truncate long URLs to prevent database column overflow
        $url = $request->fullUrl();
        $eventUrl = strlen($url) > 200 ? substr($url, 0, 200) . '...' : $url;

        activity('request')
            ->event($eventUrl)
            ->withProperties([
                'method'          => $request->method(),
                'ip'              => $request->ip(),
                'user_agent'      => $request->userAgent(),
                'response_status' => $response->status(),
            ])
            ->log('Request processed');

        return $response;
    }
}
