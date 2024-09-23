<?php

namespace App\Services;

use App\Http\Integrations\CardApi\CardApi;
use App\Http\Integrations\CardApi\Requests\CreateDeck;
use App\Http\Integrations\CardApi\Requests\DrawCard;
use App\Http\Integrations\CardApi\Requests\GetDeck;
use JsonException;
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
        try {
            $deckResponse = $this->cardApi->send(new CreateDeck($name));
            activity('card-service')->event('create-deck')->withProperties(
                [
                    'status'   => $deckResponse->status(),
                    'name'     => $name,
                    'response' => $deckResponse->json(),
                ]
            )->log('creating deck');

        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }
    }

    public function getDeck(string $name): Response
    {
        try {
            $response = $this->cardApi->send(new GetDeck($name));
            activity('card-service')->event('get-deck')->withProperties(
                [
                    'status'   => $response->status(),
                    'name'     => $name,
                    'response' => $response->json(),
                ]
            )->log('getting deck');
        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to get deck: ' . $e->getMessage());
        }

        return $response;
    }

    public function initializeDeck(string $name): array
    {
        try {
            $deckResponse = $this->getDeck($name);

            activity('card-service')->event('initialize-deck')->withProperties(
                [
                    'status'   => $deckResponse->status(),
                    'name'     => $name,
                    'response' => $deckResponse->json(),
                ]
            )->log('initializing deck');

            return match ($deckResponse->status()) {
                200     => $deckResponse->json(),
                404     => $this->createDeck($name)->json(),
                default => throw new RuntimeException('Failed to initialize deck: ' . $deckResponse->body()),
            };
        } catch (JsonException $e) {
            throw new RuntimeException('Failed to initialize deck: ' . $e->getMessage());
        }

    }
}
