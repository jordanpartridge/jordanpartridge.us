<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PerformanceTest extends DuskTestCase
{
    /**
     * Test that homepage loads within performance baseline
     * Production baseline: Sub-1s performance
     */
    public function test_homepage_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/')
                ->assertSee('Jordan Partridge');

            $loadTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds

            // Assert page loads under 3000ms (CI baseline - more lenient than production)
            $this->assertLessThan(3000, $loadTime, "Homepage took {$loadTime}ms, should be under 3000ms");
        });
    }

    /**
     * Test that blog page loads within performance baseline
     * Target: Sub-1s performance
     */
    public function test_blog_page_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/blog')
                ->assertSee('Blog');

            $loadTime = (microtime(true) - $startTime) * 1000;

            // Assert page loads under 3000ms (CI baseline - more lenient than production)
            $this->assertLessThan(3000, $loadTime, "Blog page took {$loadTime}ms, should be under 3000ms");
        });
    }

    /**
     * Test that services page loads within performance baseline
     * Target: Sub-1s performance
     */
    public function test_services_page_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/services')
                ->assertSee('Laravel Engineering & Consulting Services');

            $loadTime = (microtime(true) - $startTime) * 1000;

            // Assert page loads under 1000ms
            $this->assertLessThan(1000, $loadTime, "Services page took {$loadTime}ms, should be under 1000ms");
        });
    }

    /**
     * Test that 404 pages load within improved baseline
     * Production baseline: 3.4s max response time
     * Target: Under 2s (improvement from baseline)
     */
    public function test_404_page_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->visit('/non-existent-page')
                ->assertSee('404'); // Assuming 404 page shows this

            $loadTime = (microtime(true) - $startTime) * 1000;

            // Assert 404 page loads under 2000ms (improved from 3.4s baseline)
            $this->assertLessThan(2000, $loadTime, "404 page took {$loadTime}ms, should be under 2000ms");
        });
    }

    /**
     * Test that admin dashboard loads within baseline
     * Production baseline: 1.2s baseline
     * Target: Under 1s
     */
    public function test_admin_dashboard_performance()
    {
        $this->browse(function (Browser $browser) {
            // Skip if not authenticated - we'll handle auth separately
            if (!$this->isUserAuthenticated()) {
                $this->markTestSkipped('Admin dashboard test requires authentication');
                return;
            }

            $startTime = microtime(true);

            $browser->visit('/admin')
                ->waitFor('[data-testid="dashboard"]', 5); // Wait for dashboard to load

            $loadTime = (microtime(true) - $startTime) * 1000;

            // Assert dashboard loads under 1000ms (improved from 1.2s baseline)
            $this->assertLessThan(1000, $loadTime, "Admin dashboard took {$loadTime}ms, should be under 1000ms");
        });
    }

    /**
     * Helper method to check if user is authenticated
     */
    private function isUserAuthenticated(): bool
    {
        return auth()->check();
    }
}
