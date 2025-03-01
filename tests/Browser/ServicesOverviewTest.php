<?php

use Laravel\Dusk\Browser;

it('has correct services overview page title', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services')
            ->assertSee('Laravel Engineering & Consulting Services');
    });
});

it('displays service category grid', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services')
            ->assertPresent('.service-grid')
            ->assertSeeLink('Code Audit')
            ->assertSeeLink('Performance Optimization')
            ->assertSeeLink('Custom Development');
    });
});

it('has consultation CTA button', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services')
            ->assertSeeLink('Book a Consultation');
    });
});
