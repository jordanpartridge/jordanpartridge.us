<?php

use Laravel\Dusk\Browser;

it('shows the right name', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Jordan Partridge');
    });
});

it('can follow the engineering link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->click('@nav-engineering')
            ->assertPathIs('/engineering');
    });
});

it('can follow the home link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/engineering')
            ->click('@nav-home')
            ->assertPathIs('/');
    });
});

it('can follow the admin link', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->click('@nav-admin')
            ->assertPathIs('/admin/login');
    });
});
