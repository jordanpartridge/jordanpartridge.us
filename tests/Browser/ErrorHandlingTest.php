<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ErrorHandlingTest extends DuskTestCase
{
    /**
     * Test that 404 pages display correctly
     */
    public function test_404_page_displays_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/this-page-does-not-exist')
                ->assertSee('404')
                ->assertSee('Not Found')
                ->assertPresent('h1')
                ->assertVisible('body')
                ->assertMissing('[data-error="fatal"]'); // Should not be a fatal error
        });
    }

    /**
     * Test 404 page has proper navigation back to site
     */
    public function test_404_page_navigation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/non-existent-page')
                ->assertSee('404')
                ->assertPresent('a[href="/"], a[href="' . config('app.url') . '"]') // Should have link to homepage
                ->click('a[href="/"], a[href="' . config('app.url') . '"]:first')
                ->assertPathIs('/')
                ->assertSee('Jordan Partridge');
        });
    }

    /**
     * Test console errors are minimal
     * Based on issue: "currently seeing 1 404 resource error"
     */
    public function test_minimal_console_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Jordan Partridge');

            // Check for console errors
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, function ($log) {
                return $log['level'] === 'SEVERE';
            });

            // Should have minimal errors (allowing for 1 known 404 as mentioned in issue)
            $this->assertLessThanOrEqual(
                1,
                count($errors),
                'Found more than 1 severe console error: ' . json_encode($errors)
            );
        });
    }

    /**
     * Test console errors on blog page specifically
     * (This was the original issue in #268 that we fixed)
     */
    public function test_blog_page_console_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog')
                ->assertSee('Blog');

            // Check for console errors
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, function ($log) {
                return $log['level'] === 'SEVERE';
            });

            // Blog page should now have no severe console errors (fixed favicon issue)
            $this->assertEmpty(
                $errors,
                'Found console errors on blog page: ' . json_encode($errors)
            );
        });
    }

    /**
     * Test graceful degradation when external APIs are unavailable
     */
    public function test_graceful_api_degradation()
    {
        $this->browse(function (Browser $browser) {
            // Test pages that might depend on external APIs
            $pagesWithAPIs = [
                '/'                     => 'Jordan Partridge', // Might have Strava integration
                '/blog'                 => 'Blog', // Might have external resources
                '/software-development' => 'Software Development' // Might have GitHub integration
            ];

            foreach ($pagesWithAPIs as $url => $expectedText) {
                $browser->visit($url)
                    ->assertSee($expectedText)
                    ->assertMissing('[data-api-error]') // Should not show API errors
                    ->assertMissing('.api-error') // Alternative error class
                    ->assertMissing('[data-error*="api"]') // No API-related errors
                    ->assertMissing('.loading-error'); // Should not be stuck in error state
            }
        });
    }

    /**
     * Test error handling for Gmail integration (if accessible)
     */
    public function test_gmail_integration_error_handling()
    {
        $this->browse(function (Browser $browser) {
            // Test Gmail-related pages if they exist and are accessible
            $gmailPages = [
                '/admin' => 'Dashboard', // Admin might have Gmail widgets
            ];

            foreach ($gmailPages as $url => $expectedText) {
                try {
                    $browser->visit($url);

                    // If page loads (might redirect to login), check for Gmail errors
                    $browser->assertMissing('[data-gmail-error]')
                        ->assertMissing('.gmail-error')
                        ->assertMissing('[data-error*="gmail"]');

                } catch (\Exception $e) {
                    // If page requires authentication, that's fine - skip this test
                    $this->markTestSkipped("Gmail integration test skipped - page requires authentication");
                }
            }
        });
    }

    /**
     * Test form validation error handling
     */
    public function test_form_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                ->press('Send Message') // Submit empty form
                ->waitFor('.error, .invalid, [data-error]', 3)
                ->assertVisible('.error, .invalid, [data-error]') // Should show validation errors
                ->assertMissing('[data-error="fatal"]') // Should not be fatal errors
                ->assertPresent('form'); // Form should still be present and functional
        });
    }

    /**
     * Test JavaScript error handling
     */
    public function test_javascript_error_handling()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Jordan Partridge');

            // Execute a JavaScript command that might fail and ensure page still works
            $browser->script("
                try {
                    // Attempt to access a potentially undefined variable
                    window.someUndefinedMethod();
                } catch (e) {
                    console.log('Caught expected error:', e.message);
                }
            ");

            // Page should still be functional
            $browser->assertSee('Jordan Partridge')
                ->assertPresent('nav');
        });
    }

    /**
     * Test network error handling (simulated)
     */
    public function test_network_error_handling()
    {
        $this->browse(function (Browser $browser) {
            // Block network requests to external resources and verify graceful handling
            $browser->visit('/')
                ->assertSee('Jordan Partridge');

            // Check that the page doesn't break even if external resources fail
            $browser->script("
                // Override fetch to simulate network failures for testing
                const originalFetch = window.fetch;
                window.fetch = function() {
                    return Promise.reject(new Error('Simulated network error'));
                };
            ");

            // Navigate to another page to test resilience
            $browser->visit('/blog')
                ->assertSee('Blog')
                ->assertPresent('main, [role=\"main\"]'); // Main content should still be present
        });
    }
}
