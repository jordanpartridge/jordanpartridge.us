<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilamentLoginTest extends DuskTestCase
{
    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        // Clean up test users
        User::where('email', 'like', '%.test@example.com')->delete();

        parent::tearDown();
    }
    /**
     * Test successful login
     */
    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email'    => 'login.test@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/admin/login')
                // Wait for and type email
                ->waitFor('input[type="email"]', 10)
                ->type('input[type="email"]', $user->email)

                // Type password
                ->type('input[type="password"]', 'password123')

                // Press login button
                ->press('Login')

                // Wait and assert dashboard
                ->waitForLocation('/admin', 10)
                ->assertPathIs('/admin')
                ->assertSee('Dashboard')
                ->assertAuthenticated();
        });
    }
}
