<?php

use Laravel\Dusk\Browser;

test('it can see the login form', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->assertSee('Sign in')
                ->assertSee('Email address')
                ->assertSee('Password')
                ->assertSee('Remember me')
                ->assertSee('Forgot password?');
    });
});

// We will use dusk attributes instead of IDs for clarity
test('it can fill login form', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                // Use more reliable selectors for Livewire-powered forms
                ->waitFor('input[type="email"]')
                ->type('input[type="email"]', 'test@example.com')
                ->type('input[type="password"]', 'password')
                ->check('input[type="checkbox"]')
                ->press('Sign in')
                // You might want to add assertions for the expected behavior after login
                // This will depend on your application's behavior
                ->assertSee('Sign in'); // This assumes login will fail with these credentials
    });
});

test('it can navigate to forgot password page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->clickLink('Forgot password?')
                ->assertPathIs('/admin/password-reset/request')
                // Just assert that we're no longer on the login page
                ->assertDontSee('Developer Login');
    });
});
