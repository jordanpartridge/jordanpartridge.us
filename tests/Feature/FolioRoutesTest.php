<?php

use function Pest\Laravel\get;

test('home page returns successful response', function () {
    get('/')
        ->assertOk()
        ->assertViewIs('pages.index');
});

test('bike index page returns successful response', function () {
    get('/bike')
        ->assertOk()
        ->assertViewIs('pages.bike.index');
});

test('individual bike ride page returns successful response', function () {
    // Assuming you have a bike ride with ID 1 in your database
    get('/bike/1')
        ->assertOk()
        ->assertViewIs('pages.bike.ride');
});

test('blog index page returns successful response', function () {
    get('/blog')
        ->assertOk()
        ->assertViewIs('pages.blog.index');
});

test('individual blog post page returns successful response', function () {
    // Assuming you have a blog post with slug 'test-post' in your database
    get('/blog/test-post')
        ->assertOk()
        ->assertViewIs('pages.blog.slug');
});

test('learn index page returns successful response', function () {
    get('/learn')
        ->assertOk()
        ->assertViewIs('pages.learn.index');
});

test('individual learn topic page returns successful response', function () {
    get('/learn/php')
        ->assertOk()
        ->assertViewIs('pages.learn.topic');
});

test('profile page returns successful response', function () {
    get('/profile')
        ->assertOk()
        ->assertViewIs('pages.profile.index');
});

test('software development index page returns successful response', function () {
    get('/software-development')
        ->assertOk()
        ->assertViewIs('pages.software-development.index');
});

test('individual project page returns successful response', function () {
    get('/software-development/project-1')
        ->assertOk()
        ->assertViewIs('pages.software-development.project');
});

test('middleware is applied to all routes', function () {
    // Test global middleware
    get('/')
        ->assertMiddleware(['web']);

    // Test section-specific middleware (assuming you have auth middleware on profile)
    get('/profile')
        ->assertMiddleware(['web', 'auth']);
});

test('404 is returned for non-existent routes', function () {
    get('/non-existent-route')
        ->assertNotFound();
});

test('route parameters are properly handled', function () {
    // Test blog post parameter
    get('/blog/invalid-slug')
        ->assertNotFound();

    // Test bike ride parameter
    get('/bike/999999')
        ->assertNotFound();
});
