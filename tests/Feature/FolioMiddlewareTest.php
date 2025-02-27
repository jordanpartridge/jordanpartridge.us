<?php

use function Pest\Laravel\get;

test('global middleware is applied to all pages', function () {
    // Test that the web middleware group is working
    get('/')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/index.blade.php');
});

test('section specific middleware is properly applied', function () {
    // Public routes should be accessible
    get('/blog')
        ->assertOk();

    // Profile should require authentication
    get('/profile')
        ->assertRedirect('/login');
});

test('middleware preserves flash messages', function () {
    $this->withSession(['flash_message' => 'Test Message'])
        ->get('/')
        ->assertSessionHas('flash_message', 'Test Message');
});

test('auth middleware protects routes appropriately', function () {
    // Unauthenticated users should be redirected
    get('/profile')
        ->assertRedirect('/login');
});
