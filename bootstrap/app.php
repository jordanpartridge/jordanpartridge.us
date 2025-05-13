<?php

use App\Http\Middleware\LogRequests;
use App\Http\Middleware\RedirectToDashboard;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SecurityHeadersMiddleware::class,
            LogRequests::class,
        ]);

        $middleware->alias([
            'redirect-to-dashboard' => RedirectToDashboard::class,
            'log-requests'          => LogRequests::class,
            'role'                  => RoleMiddleware::class,
            'permission'            => PermissionMiddleware::class,
            'role_or_permission'    => RoleOrPermissionMiddleware::class,
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
