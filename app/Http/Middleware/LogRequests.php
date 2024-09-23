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

        activity('request')
            ->event($request->fullUrl())
            ->withProperties([
                'method'          => $request->method(),
                'ip'              => $request->ip(),
                'user_agent'      => $request->userAgent(),
                'response_status' => $response->status(),
            ])
            ->log('Request processed');

        return $response;
    }

    private function getRequestBody(Request $request): string
    {
        // Be careful with sensitive data
        return $request->getContent();
    }

    private function getResponseContent(Response $response): string
    {
        // Be careful with sensitive data
        return $response->getContent();
    }
}
