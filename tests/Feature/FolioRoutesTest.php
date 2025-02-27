<?php

use function Pest\Laravel\get;

test('home page returns successful response', function () {
    get('/')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/index.blade.php');
});

test('bike index page returns successful response', function () {
    get('/bike')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/bike/index.blade.php');
});

test('individual bike ride page returns successful response', function () {
    // TODO: Once ride functionality is implemented
    $this->markTestSkipped('Ride functionality not yet implemented');
});

test('blog index page returns successful response', function () {
    get('/blog')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/blog/index.blade.php');
});

test('individual blog post page returns successful response', function () {
    // TODO: Once blog functionality is implemented
    $this->markTestSkipped('Blog post functionality not yet implemented');
});

test('learn index page returns successful response', function () {
    get('/learn')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/learn/index.blade.php');
});

test('profile page requires authentication', function () {
    // Test without authentication
    get('/profile')
        ->assertRedirect('/login');
});

test('software development index page returns successful response', function () {
    get('/software-development')
        ->assertOk()
        ->assertViewIs('/Users/jordanpartridge/Sites/production/jordanpartridge/resources/views/pages/software-development/index.blade.php');
});

test('middleware functionality works correctly', function () {
    // Test session functionality (part of web middleware)
    $this->withSession(['test' => 'value'])
        ->get('/')
        ->assertSessionHas('test', 'value');

    // Test auth protection on profile
    get('/profile')
        ->assertRedirect('/login');
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
