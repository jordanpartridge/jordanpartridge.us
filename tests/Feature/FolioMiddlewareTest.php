<?php

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

test('global middleware is applied to all pages', function () {
    // Create a mock middleware
    $middlewareCalled = false;

    Route::middleware(function ($request, $next) use (&$middlewareCalled) {
        $middlewareCalled = true;
        return $next($request);
    });

    get('/');

    expect($middlewareCalled)->toBeTrue();
});

test('section specific middleware is properly applied', function () {
    // Test bike section middleware
    get('/bike')
        ->assertViewIs('pages.bike.index')
        ->assertMiddleware(['web']);

    // Test blog section middleware
    get('/blog')
        ->assertViewIs('pages.blog.index')
        ->assertMiddleware(['web']);

    // Test profile section middleware (assuming it requires auth)
    get('/profile')
        ->assertViewIs('pages.profile.index')
        ->assertMiddleware(['web', 'auth']);
});

test('middleware can modify response', function () {
    // Test that middleware can add headers
    get('/')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

test('middleware handles redirects properly', function () {
    // Test authentication middleware redirect
    get('/profile')
        ->assertRedirect('/login');
});

test('middleware preserves flash messages', function () {
    // Test that session flash messages survive middleware
    $this->withSession(['flash_message' => 'Test Message'])
        ->get('/')
        ->assertSessionHas('flash_message', 'Test Message');
});
