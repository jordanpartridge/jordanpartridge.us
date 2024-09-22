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

    public function drawCard(string $name): Response
    {
        /**
         * Should we actually capture all http exceptions here?
         * I think we can either provide a card or throw an exception, if we can't draw a card
         * Eseentially we should check for a  deck before we draw a card if we don't have a deck
         * shuld we create one? or should we throw an exception?
         */
        try {
            return $this->cardApi->send(new DrawCard($name));
        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to draw card: ' . $e->getMessage());
        }
    }

    public function createDeck(string $name): Response
    {
        try {
            return $this->cardApi->send(new CreateDeck($name));

        } catch (FatalRequestException|RequestException $e) {
            throw new RuntimeException('Failed to create deck: ' . $e->getMessage());
        }
    }

    public function getDeck(string $name): Response
    {
        return $this->cardApi->send(new GetDeck($name));
    }

    public function initializeDeck(string $name): array
    {
        try {
            $deck = $this->getDeck($name);
            if ($deck->status() === 404) {
                $deck = $this->createDeck($name);
            }

            return $deck->json();
        } catch (JsonException) {
            throw new RuntimeException('Failed to initialize deck: Invalid JSON response');
        }
    }
}
