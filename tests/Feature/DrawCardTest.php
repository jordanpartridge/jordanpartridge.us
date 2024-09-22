<?php

use App\Http\Integrations\CardApi\Requests\DrawCard;

describe(DrawCard::class, function () {
    it('has the expected resolve url', function () {
        $request = new DrawCard('test-deck', 3);
        expect($request->resolveEndpoint())->toBe('/decks/test-deck/draw?count=3');
    });

    it('handles different deck identifiers and counts', function () {
        $request1 = new DrawCard('deck-123', 1);
        expect($request1->resolveEndpoint())->toBe('/decks/deck-123/draw?count=1');

        $request2 = new DrawCard('another-deck', 10);
        expect($request2->resolveEndpoint())->toBe('/decks/another-deck/draw?count=10');
    });

    it('throws an exception for invalid count', function () {
        expect(fn () => new DrawCard('test-deck', -1))->toThrow(InvalidArgumentException::class);
    });
});
