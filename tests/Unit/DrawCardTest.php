<?php

use App\Http\Integrations\CardApi\Requests\DrawCard;
use Saloon\Enums\Method;

describe('DrawCard->__construct() ', function () {

    test('sets properties correctly', function () {
        $deckName = 'test-deck';
        $cardCount = 5;
        $request = new DrawCard($deckName, $cardCount);

        $reflection = new ReflectionClass($request);

        $deckNameProperty = $reflection->getProperty('deckName');
        expect($deckNameProperty->getValue($request))->toBe($deckName);

        $cardCountProperty = $reflection->getProperty('cardCount');
        expect($cardCountProperty->getValue($request))->toBe($cardCount);
    });

    test('uses default card count', function () {
        $request = new DrawCard('test-deck');
        $reflection = new ReflectionClass($request);
        $cardCountProperty = $reflection->getProperty('cardCount');
        expect($cardCountProperty->getValue($request))->toBe(1);
    });

    test('throws exception for invalid card count', function () {
        expect(fn () => new DrawCard('test-deck', 0))
            ->toThrow(InvalidArgumentException::class, 'Card count must be greater than 0');
    });

    test('throws exception for negative card count', function () {
        expect(fn () => new DrawCard('test-deck', -1))
            ->toThrow(InvalidArgumentException::class, 'Card count must be greater than 0');
    });
});

describe('DrawCard->resolveEndpoint', function () {
    test('returns correct endpoint', function () {
        $request = new DrawCard('test-deck', 3);
        expect($request->resolveEndpoint())->toBe('/decks/test-deck/draw?count=3');
    });

    describe('DrawCard->method', function () {
        test('is PUT method', function () {
            $request = new DrawCard('test-deck');
            expect($request->getMethod())->toBe(Method::PUT);
        });
    });
});
