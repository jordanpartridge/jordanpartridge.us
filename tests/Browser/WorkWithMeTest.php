<?php

use Laravel\Dusk\Browser;

it('has correct work with me page title', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/work-with-me')
            ->assertSee('Work With Me');
    });
});

it('displays engagement process section', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/work-with-me')
            ->assertSee('Engagement Process')
            ->assertSee('Initial Consultation')
            ->assertSee('Proposal & Agreement')
            ->assertSee('Project Execution');
    });
});

it('displays booking section with pre-qualification questions', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/work-with-me')
            ->assertSee('Book a Consultation')
            ->assertSee('Pre-Qualification Questions')
            ->assertSee('Schedule Your Consultation');
    });
});

it('displays alternative contact options', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/work-with-me')
            ->assertSee('Alternative Contact Options')
            ->assertSee('Email')
            ->assertSee('Contact Form')
            ->assertSee('LinkedIn');
    });
});
