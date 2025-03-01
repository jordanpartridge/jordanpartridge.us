<?php

use Laravel\Dusk\Browser;

it('displays code audit service details', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services/code-audit')
            ->assertSee('Code Audit')
            ->assertPresent('.service-description')
            ->assertPresent('.pricing-tiers')
            ->assertPresent('.process-steps')
            ->assertPresent('.faq-section');
    });
});

it('displays performance optimization service details', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services/performance-optimization')
            ->assertSee('Performance Optimization')
            ->assertPresent('.service-description')
            ->assertPresent('.pricing-tiers')
            ->assertPresent('.process-steps')
            ->assertPresent('.faq-section');
    });
});

it('displays custom development service details', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services/custom-development')
            ->assertSee('Custom Development')
            ->assertPresent('.service-description')
            ->assertPresent('.pricing-tiers')
            ->assertPresent('.process-steps')
            ->assertPresent('.faq-section');
    });
});
