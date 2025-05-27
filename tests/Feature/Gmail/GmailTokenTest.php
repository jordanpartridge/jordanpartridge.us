<?php

namespace Tests\Feature\Gmail;

use App\Models\GmailToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GmailTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_gmail_token()
    {
        $user = User::factory()->create();

        $token = GmailToken::create([
            'user_id'       => $user->id,
            'gmail_email'   => 'test@gmail.com',
            'account_name'  => 'Test Account',
            'is_primary'    => true,
            'access_token'  => 'access_token_123',
            'refresh_token' => 'refresh_token_123',
            'expires_at'    => now()->addHour(),
            'account_info'  => ['name' => 'Test User', 'email' => 'test@gmail.com'],
        ]);

        $this->assertDatabaseHas('gmail_tokens', [
            'user_id'     => $user->id,
            'gmail_email' => 'test@gmail.com',
            'is_primary'  => true,
        ]);

        $this->assertEquals('Test Account', $token->display_name);
        $this->assertEquals('connected', $token->status);
        $this->assertFalse($token->isExpired());
    }

    public function test_it_detects_expired_tokens()
    {
        $user = User::factory()->create();

        $token = GmailToken::create([
            'user_id'       => $user->id,
            'gmail_email'   => 'test@gmail.com',
            'access_token'  => 'access_token_123',
            'refresh_token' => 'refresh_token_123',
            'expires_at'    => now()->subHour(), // Expired
        ]);

        $this->assertTrue($token->isExpired());
        $this->assertEquals('expired', $token->status);
    }

    public function test_it_can_set_primary_account()
    {
        $user = User::factory()->create();

        // Create two accounts
        $account1 = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'account1@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token1',
            'expires_at'   => now()->addHour(),
        ]);

        $account2 = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'account2@gmail.com',
            'is_primary'   => false,
            'access_token' => 'token2',
            'expires_at'   => now()->addHour(),
        ]);

        // Set account2 as primary
        $account2->setPrimary();

        // Refresh from database
        $account1->refresh();
        $account2->refresh();

        $this->assertFalse($account1->is_primary);
        $this->assertTrue($account2->is_primary);
    }

    public function test_it_provides_display_name_fallbacks()
    {
        $user = User::factory()->create();

        // Test with account name
        $token1 = GmailToken::create([
            'user_id'      => $user->id,
            'account_name' => 'Work Account',
            'gmail_email'  => 'work@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // Test with email only
        $token2 = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'personal@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // Test with neither
        $token3 = GmailToken::create([
            'user_id'      => $user->id,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertEquals('Work Account', $token1->display_name);
        $this->assertEquals('personal@gmail.com', $token2->display_name);
        $this->assertEquals('Gmail Account', $token3->display_name);
    }

    public function test_it_generates_avatar_from_profile_or_initials()
    {
        $user = User::factory()->create();

        // Test with profile picture
        $token1 = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'test@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
            'account_info' => ['picture' => 'https://example.com/avatar.jpg'],
        ]);

        // Test with email initials
        $token2 = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'john@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // Test with no email
        $token3 = GmailToken::create([
            'user_id'      => $user->id,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertEquals('https://example.com/avatar.jpg', $token1->avatar);
        $this->assertTrue($token1->is_avatar_image);

        $this->assertEquals('JO', $token2->avatar);
        $this->assertFalse($token2->is_avatar_image);

        $this->assertEquals('GM', $token3->avatar);
        $this->assertFalse($token3->is_avatar_image);
    }

    public function test_active_scope_filters_expired_tokens()
    {
        $user = User::factory()->create();

        // Active token
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'active@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        // Expired token
        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'expired@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->subHour(),
        ]);

        $activeTokens = GmailToken::active()->get();

        $this->assertCount(1, $activeTokens);
        $this->assertEquals('active@gmail.com', $activeTokens->first()->gmail_email);
    }

    public function test_primary_scope_returns_primary_account()
    {
        $user = User::factory()->create();

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'secondary@gmail.com',
            'is_primary'   => false,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'primary@gmail.com',
            'is_primary'   => true,
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $primary = GmailToken::primary($user->id)->first();

        $this->assertNotNull($primary);
        $this->assertEquals('primary@gmail.com', $primary->gmail_email);
        $this->assertTrue($primary->is_primary);
    }

    public function test_it_formats_last_sync_time()
    {
        $user = User::factory()->create();

        $token = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'test@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
            'last_sync_at' => now()->subMinutes(30),
        ]);

        $this->assertStringContainsString('30 minutes ago', $token->last_sync_format);
    }

    public function test_token_belongs_to_user()
    {
        $user = User::factory()->create();

        $token = GmailToken::create([
            'user_id'      => $user->id,
            'gmail_email'  => 'test@gmail.com',
            'access_token' => 'token',
            'expires_at'   => now()->addHour(),
        ]);

        $this->assertEquals($user->id, $token->user->id);
        $this->assertEquals($user->name, $token->user->name);
    }
}
