<?php

namespace Tests\Feature\Gmail;

use App\Models\GmailToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PartridgeRocks\GmailClient\GmailClient;
use Tests\TestCase;

class UserGmailIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_user_has_gmail_accounts_relationship()
    {
        $user = User::factory()->create();

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'account1@gmail.com',
            'access_token' => 'token1',
            'expires_at'   => now()->addHour(),
        ]);

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'account2@gmail.com',
            'access_token' => 'token2',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertCount(2, $user->gmailAccounts);
        $this->assertEquals('account1@gmail.com', $user->gmailAccounts->first()->gmail_email);
    }

    public function test_user_has_primary_gmail_account_relationship()
    {
        $user = User::factory()->create();

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'secondary@gmail.com',
            'is_primary'   => false,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $primary = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'primary@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertEquals($primary->id, $user->primaryGmailAccount->id);
        $this->assertEquals('primary@gmail.com', $user->primaryGmailAccount->gmail_email);
    }

    public function test_gmail_token_relationship_returns_primary_account()
    {
        $user = User::factory()->create();

        $primary = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'primary@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // gmailToken() should return the primary account for backwards compatibility
        $this->assertEquals($primary->id, $user->gmailToken->id);
    }

    public function test_has_valid_gmail_token_checks_any_active_account()
    {
        $user = User::factory()->create();

        // No tokens
        $this->assertFalse($user->hasValidGmailToken());

        // Expired token
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'expired@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->subHour(),
        ]);

        $this->assertFalse($user->hasValidGmailToken());

        // Active token
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'active@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertTrue($user->hasValidGmailToken());
    }

    public function test_has_valid_primary_gmail_token_checks_only_primary()
    {
        $user = User::factory()->create();

        // Active secondary account
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'secondary@gmail.com',
            'is_primary'   => false,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertFalse($user->hasValidPrimaryGmailToken());

        // Active primary account
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'primary@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // Refresh the relationship
        $user = $user->fresh();
        $this->assertTrue($user->hasValidPrimaryGmailToken());
    }

    public function test_get_gmail_client_returns_null_for_expired_token()
    {
        $user = User::factory()->create();

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'expired@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token',
            'expires_at'   => now()->subHour(),
        ]);

        $this->assertNull($user->getGmailClient());
    }

    public function test_get_gmail_client_returns_null_when_no_token()
    {
        $user = User::factory()->create();

        $this->assertNull($user->getGmailClient());
    }

    public function test_get_gmail_client_for_account_returns_correct_client()
    {
        $user = User::factory()->create();

        GmailToken::create([
            'user_id'       => $user->id,
            'gmail_email'   => 'work@gmail.com',
            'access_token'  => 'work_token',
            'refresh_token' => 'work_refresh',
            'expires_at'    => now()->addHour(),
        ]);

        // Mock the GmailClient
        $this->app->instance(GmailClient::class, Mockery::mock(GmailClient::class, function ($mock) {
            $mock->shouldReceive('authenticate')
                ->with('work_token', 'work_refresh', Mockery::type(\DateTime::class))
                ->once()
                ->andReturnSelf();
        }));

        $client = $user->getGmailClientForAccount('work@gmail.com');

        $this->assertInstanceOf(GmailClient::class, $client);
    }

    public function test_get_gmail_client_for_account_returns_null_for_nonexistent_account()
    {
        $user = User::factory()->create();

        $this->assertNull($user->getGmailClientForAccount('nonexistent@gmail.com'));
    }

    public function test_gmail_accounts_count_attribute()
    {
        $user = User::factory()->create();

        $this->assertEquals(0, $user->gmail_accounts_count);

        // Add expired token (should not count)
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'expired@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->subHour(),
        ]);

        $this->assertEquals(0, $user->fresh()->gmail_accounts_count);

        // Add active token
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'active@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertEquals(1, $user->fresh()->gmail_accounts_count);
    }

    public function test_connected_gmail_emails_attribute()
    {
        $user = User::factory()->create();

        $this->assertEquals([], $user->connected_gmail_emails);

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'active@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'expired@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->subHour(),
        ]);

        // Should only include active accounts
        $emails = $user->fresh()->connected_gmail_emails;
        $this->assertCount(1, $emails);
        $this->assertEquals('active@gmail.com', $emails[0]);
    }
}
