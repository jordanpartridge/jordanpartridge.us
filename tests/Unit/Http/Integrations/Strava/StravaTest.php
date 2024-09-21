<?php

use App\Http\Integrations\Strava\Strava;

it('has proper base url', function () {
    $connector = new Strava();
    expect($connector->resolveBaseUrl())->toBe('https://www.strava.com/api/v3');
});
