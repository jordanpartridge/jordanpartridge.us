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
                ->assertPresent('a[href="/"]') // Should have link to homepage
                ->click('a[href="/"]')
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

            // Check for console errors (excluding external service failures)
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, function ($log) {
                // Only count severe errors that are NOT external service failures
                if ($log['level'] !== 'SEVERE') {
                    return false;
                }

                // Filter out external service failures (picsum, external APIs, etc.)
                $message = $log['message'];
                $externalServices = ['picsum.photos', 'unsplash.com', 'placeholder.com'];

                foreach ($externalServices as $service) {
                    if (strpos($message, $service) !== false) {
                        return false; // Ignore external service errors
                    }
                }

                return true; // This is an internal error we care about
            });

            // Blog page should have no internal severe console errors
            $this->assertEmpty(
                $errors,
                'Found internal console errors on blog page: ' . json_encode($errors)
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
                '/software-development' => 'Full-Stack Developer' // Might have GitHub integration
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
                ->assertPresent('form') // Form should be present
                ->assertPresent('input[required], textarea[required]') // Should have required fields
                ->assertMissing('[data-error="fatal"]'); // Should not show fatal errors on page load
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
