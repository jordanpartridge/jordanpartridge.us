<?php

namespace App\Domain\BlackJack\DataTransferObjects;

use InvalidArgumentException;

class PlayerDto implements \JsonSerializable
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_HITTING = 'hitting';
    public const STATUS_STANDING = 'standing';
    public const STATUS_BUST = 'bust';
    public const STATUS_BLACKJACK = 'blackjack';
    public const STATUS_SURRENDERED = 'surrendered';

    private const VALID_STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_HITTING,
        self::STATUS_STANDING,
        self::STATUS_BUST,
        self::STATUS_BLACKJACK,
        self::STATUS_SURRENDERED,
    ];

    private const BLACKJACK_SCORE = 21;

    /**
     * @param string $name
     * @param array<array{suit: string, rank: string}> $hand
     * @param int $score
     * @param string $status
     */
    public function __construct(
        public readonly string $name,
        public readonly array $hand = [],
        public readonly int $score = 0,
        public readonly string $status = self::STATUS_WAITING
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('Player name cannot be empty');
        }

        if (!in_array($status, self::VALID_STATUSES)) {
            throw new InvalidArgumentException("Invalid player status: {$status}");
        }
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Player data must contain name');
        }

        return new self(
            name: $data['name'],
            hand: $data['hand'] ?? [],
            score: $data['score'] ?? 0,
            status: $data['status'] ?? self::STATUS_WAITING
        );
    }

    public function toArray(): array
    {
        return [
            'name'   => $this->name,
            'hand'   => $this->hand,
            'score'  => $this->score,
            'status' => $this->status,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function withHand(array $hand): self
    {
        $score = $this->calculateScore($hand);
        $status = $this->determineStatus($score);

        return new self(
            name: $this->name,
            hand: $hand,
            score: $score,
            status: $status
        );
    }

    public function withStatus(string $status): self
    {
        if (!in_array($status, self::VALID_STATUSES)) {
            throw new InvalidArgumentException("Invalid player status: {$status}");
        }

        return new self(
            name: $this->name,
            hand: $this->hand,
            score: $this->score,
            status: $status
        );
    }

    public function canHit(): bool
    {
        return !in_array($this->status, [
            self::STATUS_STANDING,
            self::STATUS_BUST,
            self::STATUS_BLACKJACK,
            self::STATUS_SURRENDERED,
        ]);
    }

    public function canStand(): bool
    {
        return $this->status === self::STATUS_HITTING;
    }

    public function canSurrender(): bool
    {
        return $this->status === self::STATUS_WAITING && count($this->hand) === 2;
    }

    private function calculateScore(array $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand as $card) {
            if (!isset($card['rank'])) {
                throw new InvalidArgumentException('Invalid card data: missing rank');
            }

            if ($card['rank'] === 'A') {
                $aces++;
                $score += 11;
            } else {
                $score += match ($card['rank']) {
                    'K', 'Q', 'J' => 10,
                    default => $this->validateNumericRank($card['rank']),
                };
            }
        }

        // Adjust for aces if score is over 21
        while ($score > self::BLACKJACK_SCORE && $aces > 0) {
            $score -= 10;
            $aces--;
        }

        return $score;
    }

    private function validateNumericRank(string $rank): int
    {
        $value = (int) $rank;
        if ($value < 2 || $value > 10) {
            throw new InvalidArgumentException("Invalid numeric rank: {$rank}");
        }
        return $value;
    }

    private function determineStatus(int $score): string
    {
        if ($score > self::BLACKJACK_SCORE) {
            return self::STATUS_BUST;
        }

        if ($score === self::BLACKJACK_SCORE && count($this->hand) === 2) {
            return self::STATUS_BLACKJACK;
        }

        if ($this->status === self::STATUS_WAITING) {
            return self::STATUS_HITTING;
        }

        return $this->status;
    }
}
