<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CoreFunctionalityTest extends DuskTestCase
{
    /**
     * Test homepage navigation and content loading
     */
    public function test_homepage_content_and_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Jordan Partridge')
                ->assertPresent('@nav-home')
                ->assertPresent('@nav-contact')
                ->assertPresent('@nav-bike') // Fat Bike Corps navigation
                ->assertVisible('header');
        });
    }

    /**
     * Test blog posts accessibility and loading
     */
    public function test_blog_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog')
                ->assertSee('Blog')
                ->assertSee('Jordan\'s Blog')
                ->assertPresent('.blog-post, article, [data-testid="blog-post"]')
                ->assertVisible('h1')
                ->assertMissing('[data-error]'); // Ensure no error states
        });
    }

    /**
     * Test blog post detail page functionality
     */
    public function test_blog_post_detail_functionality()
    {
        $this->browse(function (Browser $browser) {
            // First check if there are any blog posts
            $browser->visit('/blog');

            $postLinks = $browser->elements('a[href*="blog/"]');

            if (count($postLinks) > 0) {
                // Click on first blog post
                $browser->click('a[href*="blog/"]:first-of-type')
                    ->assertPathBeginsWith('/blog/')
                    ->assertVisible('h1')
                    ->assertVisible('[role="main"], main, .content')
                    ->assertMissing('[data-error]');
            } else {
                $this->markTestSkipped('No blog posts available to test');
            }
        });
    }

    /**
     * Test contact form functionality
     */
    public function test_contact_form_functionality()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                ->assertSee('Contact')
                ->assertPresent('form')
                ->assertPresent('input[name="name"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('textarea[name="message"]')
                ->assertPresent('button[type="submit"]');
        });
    }

    /**
     * Test contact form validation
     */
    public function test_contact_form_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                ->press('Send Message') // Try to submit empty form
                ->waitFor('.error, .invalid, [data-error]', 3) // Wait for validation errors
                ->assertVisible('.error, .invalid, [data-error]'); // Should show validation errors
        });
    }

    /**
     * Test contact form successful submission
     */
    public function test_contact_form_submission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                ->type('name', 'Test User')
                ->type('email', 'test@example.com')
                ->type('message', 'This is a test message from the E2E test suite.')
                ->press('button[type="submit"]')
                ->waitFor('.success, .thank-you, [data-success]', 5) // Wait for success message
                ->assertVisible('.success, .thank-you, [data-success]'); // Should show success
        });
    }

    /**
     * Test Strava integration display (verify data shows without errors)
     */
    public function test_strava_integration_display()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertMissing('[data-strava-error]') // Should not show Strava errors
                ->assertMissing('.strava-error') // Alternative error class
                ->assertMissing('[data-error*="strava"]'); // No Strava-related errors

            // If there's a Strava section, verify it loads properly
            $stravaElements = $browser->elements('[data-strava], .strava-widget, .ride-data');

            if (count($stravaElements) > 0) {
                $browser->assertMissing('[data-loading]') // Should not be stuck loading
                    ->assertMissing('.loading-error'); // Should not show loading errors
            }
        });
    }

    /**
     * Test navigation between main sections
     */
    public function test_navigation_flow()
    {
        $this->browse(function (Browser $browser) {
            // Test full navigation flow
            $browser->visit('/')
                ->click('@nav-bike')
                ->assertPathIs('/bike')
                ->assertSee('Fat Bike Corps')

                ->click('@nav-contact')
                ->assertPathIs('/contact')
                ->assertSee('Contact')

                ->click('@nav-home')
                ->assertPathIs('/')
                ->assertSee('Jordan Partridge');
        });
    }

    /**
     * Test responsive design elements
     */
    public function test_responsive_design()
    {
        $this->browse(function (Browser $browser) {
            // Test mobile viewport
            $browser->resize(375, 667) // iPhone SE dimensions
                ->visit('/')
                ->assertSee('Jordan Partridge')
                ->assertPresent('nav') // Navigation should still be present
                ->assertMissing('[data-desktop-only]'); // Desktop-only elements should be hidden

            // Test desktop viewport
            $browser->resize(1920, 1080)
                ->visit('/')
                ->assertSee('Jordan Partridge')
                ->assertPresent('nav');
        });
    }

    /**
     * Test dark mode functionality if available
     */
    public function test_dark_mode_toggle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            // Look for dark mode toggle
            $darkModeToggle = $browser->elements('[data-theme-toggle], .dark-mode-toggle, [aria-label*="theme"]');

            if (count($darkModeToggle) > 0) {
                $browser->click('[data-theme-toggle], .dark-mode-toggle, [aria-label*="theme"]:first')
                    ->pause(500) // Wait for theme change
                    ->assertPresent('.dark, [data-theme="dark"], html.dark'); // Should apply dark theme
            } else {
                $this->markTestSkipped('No dark mode toggle found');
            }
        });
    }
}
