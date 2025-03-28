<?php

use Laravel\Dusk\Browser;

test('it loads homepage', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee('Jordan');
    });
});

test('it can be redirected to login', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->assertSee('Sign in')
                ->press('Developer Login');
    });
});

// We'll test login form in a separate file to avoid issues with the page state
