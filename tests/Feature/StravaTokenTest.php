<?php

use App\Models\StravaToken;

it('can be created with factory', function () {
    $token = StravaToken::factory()->create();

    expect($token->access_token)->toBeString();
    expect($token->expires_at)->toBeInstanceOf(DateTime::class);
    expect($token->refresh_token)->toBeString();
    expect($token->athlete_id)->toBeInt();

    $this->assertDatabaseHas('strava_tokens', [
        'expires_at'    => $token->expires_at,
        'refresh_token' => $token->refresh_token,
        'athlete_id'    => $token->athlete_id,
    ]);
});

it('does not store tokens in plain text', function () {
    $accessToken = 'access';
    StravaToken::factory()->state(['access_token' => $accessToken])->create();

    $this->assertDatabaseMissing('strava_tokens', [
        'access_token' => $accessToken,
    ]);
});
