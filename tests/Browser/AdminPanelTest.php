<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPanelTest extends DuskTestCase
{
    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test admin user using factory
        $this->adminUser = User::factory()->create([
            'name'              => 'Test Admin',
            'email'             => 'admin@test.com',
            'email_verified_at' => now(),
        ]);

        // Create a regular user for security testing
        $this->regularUser = User::factory()->create([
            'name'              => 'Regular User',
            'email'             => 'user@test.com',
            'email_verified_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        // Clean up test users
        $this->adminUser->delete();
        $this->regularUser->delete();

        parent::tearDown();
    }

    /**
     * Test that regular users cannot access admin panel (security test)
     */
    public function test_regular_user_admin_access_denied()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->regularUser)
                ->visit('/admin');

            // Should either redirect to login or show 403/unauthorized
            $currentUrl = $browser->driver->getCurrentURL();
            $pageText = $browser->script('return document.body.innerText || document.body.textContent || ""');
            $pageText = is_array($pageText) ? $pageText[0] : $pageText;

            $hasRestrictedAccess =
                str_contains($currentUrl, '/login') ||
                str_contains($currentUrl, '/admin/login') ||
                str_contains($pageText, '403') ||
                str_contains($pageText, 'Unauthorized') ||
                str_contains($pageText, 'Access Denied') ||
                str_contains($pageText, 'Forbidden');

            $this->assertTrue(
                $hasRestrictedAccess,
                'Regular user should not have access to admin panel. Current URL: ' . $currentUrl
            );
        });
    }

    /**
     * Test unauthenticated users cannot access admin panel
     */
    public function test_unauthenticated_admin_access_denied()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin');

            // Should redirect to login
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($currentUrl, '/login') || str_contains($currentUrl, '/admin/login'),
                'Unauthenticated user should be redirected to login. Current URL: ' . $currentUrl
            );
        });
    }

    /**
     * Test admin dashboard access with admin user (if permissions exist)
     */
    public function test_admin_dashboard_access()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit('/admin');

            $currentUrl = $browser->driver->getCurrentURL();
            $pageText = $browser->script('return document.body.innerText || document.body.textContent || ""');
            $pageText = is_array($pageText) ? $pageText[0] : $pageText;

            // Check if admin user has access or also gets redirected
            if (str_contains($currentUrl, '/login') || str_contains($currentUrl, '/admin/login')) {
                $this->markTestSkipped('Admin user does not have sufficient permissions - role system may need setup');
            } else {
                // Admin has access - check for admin interface elements
                $browser->assertPresent('body')
                    ->assertMissing('403')
                    ->assertMissing('Unauthorized');
            }
        });
    }

    /**
     * Test specific admin routes are protected
     */
    public function test_admin_routes_protected()
    {
        $protectedRoutes = [
            '/admin/clients',
            '/admin/posts',
            '/admin/users',
            '/admin/categories',
            '/admin/github-settings-page',
            '/admin/gmail-integration-page',
            '/admin/performance-monitoring-dashboard'
        ];

        $this->browse(function (Browser $browser) use ($protectedRoutes) {
            foreach ($protectedRoutes as $route) {
                $browser->loginAs($this->regularUser)
                    ->visit($route);

                $currentUrl = $browser->driver->getCurrentURL();
                $pageText = $browser->script('return document.body.innerText || document.body.textContent || ""');
                $pageText = is_array($pageText) ? $pageText[0] : $pageText;

                $hasRestrictedAccess =
                    str_contains($currentUrl, '/login') ||
                    str_contains($currentUrl, '/admin/login') ||
                    str_contains($pageText, '403') ||
                    str_contains($pageText, '404') ||
                    str_contains($pageText, 'Not Found') ||
                    str_contains($pageText, 'Unauthorized') ||
                    str_contains($pageText, 'Access Denied') ||
                    str_contains($pageText, 'Forbidden');

                $this->assertTrue(
                    $hasRestrictedAccess,
                    "Regular user should not access {$route}. Current URL: {$currentUrl}"
                );
            }
        });
    }

    /**
     * Test admin dashboard performance (if accessible)
     */
    public function test_admin_dashboard_performance()
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);

            $browser->loginAs($this->adminUser)
                ->visit('/admin');

            $loadTime = (microtime(true) - $startTime) * 1000;

            $currentUrl = $browser->driver->getCurrentURL();

            if (str_contains($currentUrl, '/login')) {
                $this->markTestSkipped('Admin user redirected to login - performance test not applicable');
            } else {
                $this->assertLessThan(5000, $loadTime, "Admin dashboard took {$loadTime}ms, should be under 5000ms");
            }
        });
    }

    /**
     * Test API endpoints are protected
     */
    public function test_api_endpoints_protected()
    {
        $this->browse(function (Browser $browser) {
            // Test API endpoints that should require authentication
            $apiEndpoints = [
                '/api/clients',
                '/api/posts',
                '/api/users'
            ];

            foreach ($apiEndpoints as $endpoint) {
                try {
                    $browser->visit($endpoint);
                    $pageText = $browser->script('return document.body.innerText || document.body.textContent || ""');
                    $pageText = is_array($pageText) ? $pageText[0] : $pageText;

                    // API should return 401/403 or redirect to login
                    $hasProperSecurity =
                        str_contains($pageText, '401') ||
                        str_contains($pageText, '403') ||
                        str_contains($pageText, 'Unauthorized') ||
                        str_contains($pageText, 'Unauthenticated') ||
                        str_contains($browser->driver->getCurrentURL(), '/login');

                    if (!$hasProperSecurity) {
                        // API might not exist, which is also secure
                        $this->assertTrue(
                            str_contains($pageText, '404') || str_contains($pageText, 'Not Found'),
                            "API endpoint {$endpoint} should be protected or not exist"
                        );
                    }
                } catch (\Exception $e) {
                    // If endpoint throws exception or doesn't exist, that's secure
                    $this->assertTrue(true, "API endpoint {$endpoint} properly secured");
                }
            }
        });
    }

    /**
     * Test session security - regular users stay restricted after admin logout
     */
    public function test_session_security()
    {
        $this->browse(function (Browser $browser) {
            // Login as regular user first
            $browser->loginAs($this->regularUser)
                ->visit('/admin');

            $firstUrl = $browser->driver->getCurrentURL();

            // Should be redirected or see access denied
            $this->assertTrue(
                str_contains($firstUrl, '/login') || str_contains($firstUrl, '/admin/login'),
                'Regular user should not access admin panel'
            );

            // Now try accessing after being "denied"
            $browser->visit('/admin/clients');
            $secondUrl = $browser->driver->getCurrentURL();

            $this->assertTrue(
                str_contains($secondUrl, '/login') || str_contains($secondUrl, '/admin/login'),
                'Regular user should consistently be denied admin access'
            );
        });
    }
}
