<?php

namespace Tests\Feature\Routes;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('email verification route', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create(['email_verified_at' => null]);
    $this->actingAs($user);

    $verificationUrl = URL::temporarySignedRoute(
        'verification:verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->get($verificationUrl);

    $response->assertStatus(302);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

test('logout route', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertStatus(302);
    $this->assertGuest();
});

test('strava redirect route', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/strava/redirect');

    $response->assertStatus(302);
    $response->assertRedirect();
});

test('strava callback route', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/strava/callback', ['code' => 'test_code']);

    //test code is invalid so we expect a 400 response
    $response->assertStatus(400);
});
