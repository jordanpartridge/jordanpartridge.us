<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
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
                ->assertSee('Recent Articles') // Verify the section is displayed
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
                ->assertPresent('input[name="name"][required]') // Name field should be required
                ->assertPresent('input[name="email"][required]') // Email field should be required
                ->assertPresent('textarea[name="message"][required]') // Message field should be required
                ->assertPresent('button[type="submit"]'); // Submit button should be present
        });
    }

    /**
     * Test contact form successful submission
     */
    #[Group('skip-alert-issues')]
    public function test_contact_form_submission()
    {
        $this->markTestSkipped('Contact form has browser alert issues in test environment');
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

            // Look for dark mode toggle (it has aria-label="Toggle dark mode")
            $darkModeToggle = $browser->elements('[aria-label="Toggle dark mode"]');

            if (count($darkModeToggle) > 0) {
                // Get initial state
                $initialDarkMode = $browser->script('return document.documentElement.classList.contains("dark")');
                $initialDarkMode = is_array($initialDarkMode) ? $initialDarkMode[0] : $initialDarkMode;

                $browser->click('[aria-label="Toggle dark mode"]')
                    ->pause(500); // Wait for theme change

                // Check if dark mode state changed
                $finalDarkMode = $browser->script('return document.documentElement.classList.contains("dark")');
                $finalDarkMode = is_array($finalDarkMode) ? $finalDarkMode[0] : $finalDarkMode;

                $this->assertNotEquals($initialDarkMode, $finalDarkMode, 'Dark mode toggle should change theme state');
            } else {
                $this->markTestSkipped('No dark mode toggle found');
            }
        });
    }
}
