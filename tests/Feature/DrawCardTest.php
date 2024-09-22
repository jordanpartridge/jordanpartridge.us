<?php

use App\Http\Integrations\CardApi\Requests\DrawCard;

it('has the expected resolve url', function () {
    $request = new DrawCard('test-deck', 3);
    expect($request->resolveEndpoint())->toBe('/decks/test-deck/draw?count=3');
});
