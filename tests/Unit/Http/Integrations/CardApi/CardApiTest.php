<?php

use App\Http\Integrations\CardApi\CardApi;

it('has proper base url', function () {
    $connector = new CardApi('some-token', 'https://card-api.jordanpartridge.us/v1/');
    expect($connector->resolveBaseUrl())->toBe('https://card-api.jordanpartridge.us/v1/');
});
