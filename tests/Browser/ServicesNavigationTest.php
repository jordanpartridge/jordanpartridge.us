 <?php

use Laravel\Dusk\Browser;

it('has services link in main navigation', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSeeLink('Services');
    });
});

it('has work with me button in header', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSeeLink('Work With Me');
    });
});

it('can navigate to services page from main navigation', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->clickLink('Services')
            ->assertPathIs('/services')
            ->assertSee('Laravel Engineering & Consulting Services');
    });
});

it('can navigate to work with me page from header button', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->clickLink('Work With Me')
            ->assertPathIs('/work-with-me')
            ->assertSee('Work With Me');
    });
});

it('can navigate to individual service pages from services overview', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/services')
            ->clickLink('Code Audit')
            ->assertPathIs('/services/code-audit')
            ->assertSee('Laravel Code Audit Service');

        $browser->visit('/services')
            ->clickLink('Performance Optimization')
            ->assertPathIs('/services/performance-optimization')
            ->assertSee('Laravel Performance Optimization');

        $browser->visit('/services')
            ->clickLink('Custom Development')
            ->assertPathIs('/services/custom-development')
            ->assertSee('Laravel Custom Development');
    });
});
