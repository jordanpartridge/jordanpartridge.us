<?php

use App\Http\Integrations\CardApi\CardApi;

it('has proper base url', function () {
    $connector = new CardApi();
    expect($connector->resolveBaseUrl())->toBe('https://card-api.jordanpartridge.us/v1/');
});
