<?php

namespace App\Domain\BlackJack\DataTransferObjects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class GameDto implements \JsonSerializable
{
    public const STATUS_INITIALIZED = 'initialized';
    public const STATUS_DEALING = 'dealing';
    public const STATUS_PLAYER_TURNS = 'player_turns';
    public const STATUS_DEALER_TURN = 'dealer_turn';
    public const STATUS_COMPLETE = 'complete';

    private const VALID_STATUSES = [
        self::STATUS_INITIALIZED,
        self::STATUS_DEALING,
        self::STATUS_PLAYER_TURNS,
        self::STATUS_DEALER_TURN,
        self::STATUS_COMPLETE,
    ];

    /**
     * @param string $name
     * @param string $deckSlug
     * @param Collection<int, PlayerDto> $players
     * @param array<array{suit: string, rank: string}> $dealerHand
     * @param string $status
     */
    public function __construct(
        public readonly string $name,
        public readonly string $deckSlug,
        public readonly Collection $players,
        public readonly array $dealerHand = [],
        public readonly string $status = self::STATUS_INITIALIZED
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('Game name cannot be empty');
        }

        if (empty($deckSlug)) {
            throw new InvalidArgumentException('Deck slug cannot be empty');
        }

        if (!in_array($status, self::VALID_STATUSES)) {
            throw new InvalidArgumentException("Invalid game status: {$status}");
        }

        $this->validateDealerHand($dealerHand);
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['name'], $data['deck_slug'])) {
            throw new InvalidArgumentException('Game data must contain name and deck_slug');
        }

        return new self(
            name: $data['name'],
            deckSlug: $data['deck_slug'],
            players: collect($data['players'] ?? [])->map(fn (array $player) => PlayerDto::fromArray($player)),
            dealerHand: $data['dealer_hand'] ?? [],
            status: $data['status'] ?? self::STATUS_INITIALIZED
        );
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'deck_slug'   => $this->deckSlug,
            'players'     => $this->players->map(fn (PlayerDto $player) => $player->toArray())->all(),
            'dealer_hand' => $this->dealerHand,
            'status'      => $this->status,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function withStatus(string $status): self
    {
        if (!in_array($status, self::VALID_STATUSES)) {
            throw new InvalidArgumentException("Invalid game status: {$status}");
        }

        return new self(
            name: $this->name,
            deckSlug: $this->deckSlug,
            players: $this->players,
            dealerHand: $this->dealerHand,
            status: $status
        );
    }

    public function withDealerHand(array $hand): self
    {
        $this->validateDealerHand($hand);

        return new self(
            name: $this->name,
            deckSlug: $this->deckSlug,
            players: $this->players,
            dealerHand: $hand,
            status: $this->status
        );
    }

    public function canDeal(): bool
    {
        return $this->status === self::STATUS_INITIALIZED;
    }

    public function canStartPlayerTurns(): bool
    {
        return $this->status === self::STATUS_DEALING &&
               count($this->dealerHand) === 2 &&
               $this->players->every(fn (PlayerDto $player) => count($player->hand) === 2);
    }

    public function canStartDealerTurn(): bool
    {
        return $this->status === self::STATUS_PLAYER_TURNS &&
               $this->players->every(
                   fn (PlayerDto $player) => in_array($player->status, [
                       PlayerDto::STATUS_STANDING,
                       PlayerDto::STATUS_BUST,
                       PlayerDto::STATUS_BLACKJACK,
                       PlayerDto::STATUS_SURRENDERED
                   ])
               );
    }

    public function isComplete(): bool
    {
        return $this->status === self::STATUS_COMPLETE;
    }

    /**
     * @param array<array{suit: string, rank: string}> $hand
     */
    private function validateDealerHand(array $hand): void
    {
        foreach ($hand as $card) {
            if (!isset($card['suit'], $card['rank'])) {
                throw new InvalidArgumentException('Invalid dealer hand: each card must have suit and rank');
            }
        }
    }
}
