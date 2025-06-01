<?php

use Laravel\Dusk\Browser;

it('shows the right name', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Jordan Partridge');
    });
});

it('can follow the software-development link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->pause(1000) // Wait for Alpine.js to load
            ->press('Software') // Try pressing the button by text
            ->pause(500) // Wait for dropdown
            ->click('@nav-software-development')
            ->assertPathIs('/software-development');
    });
});

it('can follow the home link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/software-development')
            ->click('@nav-home')
            ->assertPathIs('/');
    });
});

it('can follow the contact link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->click('@nav-contact')
            ->assertPathIs('/contact');
    });
});
