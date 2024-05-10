<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('redirect returns an ok response', function () {

    Sanctum::actingAs(
        User::factory()->create()
    );

    $response = $this->get(route('strava.redirect'));

    $query = http_build_query([
        'client_id'     => config('services.strava.client_id'),
        'redirect_uri'  => route('strava.callback'),
        'response_type' => 'code',
        'scope'         => 'read,activity:read_all',

    ]);

    $response->assertRedirect('https://www.strava.com/oauth/authorize?' . $query);


});

// test cases...
