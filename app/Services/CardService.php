<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use RuntimeException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

final readonly class CardService
{
    public function __construct(private CardApi $cardApi)
    {
    }

    public function drawCard(string $name, int $number = 1): Response
    {

        try {
            $response = $this->cardApi->send(new DrawCard($name, $number));
            activity('card-service')
                ->event('draw-card')
                ->withProperties(
                    [
                        'name'     => $name,
                        'number'   => $number,
                        'response' => $response->json(),
                    ]
                )->log('drawing card');

            return $response;
        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to draw card: ' . $e->getMessage());
        }
    }

    public function createDeck(string $name): Response
    {
        $deckResponse = $this->cardApi->send(new CreateDeck($name));
        activity('card-service')->event('create-deck')->withProperties(
            [
                'status'   => $deckResponse->status(),
                'name'     => $name,
                'response' => $deckResponse->json(),
            ]
        )->log('creating deck');

        return $deckResponse;
    }

    public function getDeck(string $name): Response
    {
        return $this->cardApi->send(new GetDeck($name));

    }

    public function initializeDeck(string $name): Response
    {

        $deckResponse = $this->createDeck($name);

        activity('card-service')->event('initialize-deck')->withProperties(
            [
                'status'   => $deckResponse->status(),
                'name'     => $name,
                'response' => $deckResponse->json(),
            ]
        )->log('initializing deck');
        return $deckResponse;
    }
}
