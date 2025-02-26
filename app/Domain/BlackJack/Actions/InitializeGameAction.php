<?php

namespace App\Domain\BlackJack\Actions;

use App\Domain\BlackJack\DataTransferObjects\GameDto;
use App\Domain\BlackJack\DataTransferObjects\PlayerDto;
use App\Domain\BlackJack\Events\GameInitializedEvent;
use App\Domain\BlackJack\ValueObjects\Deck;
use App\Contracts\CardServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class InitializeGameAction
{
    private const MAX_RETRY_ATTEMPTS = 3;
    private const RETRY_DELAY_MS = 1000;

    public function __construct(
        private readonly CardServiceInterface $cardApi
    ) {
    }

    /**
     * Initialize a new blackjack game.
     *
     * @param string $name The name of the game
     * @param array<string> $players Array of player names
     * @throws InvalidArgumentException If input validation fails
     * @throws RuntimeException If deck initialization fails
     * @return GameDto
     */
    public function execute(string $name, array $players): GameDto
    {
        $this->validateInput($name, $players);

        try {
            $deck = $this->initializeDeckWithRetry($name);
            $playerDtos = $this->createPlayerDtos($players);
            $gameDto = $this->createGameDto($name, $deck, $playerDtos);

            GameInitializedEvent::dispatch($gameDto);

            Log::info('Game initialized successfully', [
                'game'         => $name,
                'deck_slug'    => $deck->slug(),
                'player_count' => count($players)
            ]);

            return $gameDto;

        } catch (RuntimeException $e) {
            Log::error('Failed to initialize game', [
                'game'  => $name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * @param string $name
     * @param array<string> $players
     * @throws InvalidArgumentException
     */
    private function validateInput(string $name, array $players): void
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Game name cannot be empty');
        }

        if (empty($players)) {
            throw new InvalidArgumentException('At least one player is required');
        }

        foreach ($players as $player) {
            if (!is_string($player) || empty($player)) {
                throw new InvalidArgumentException('All player names must be non-empty strings');
            }
        }
    }

    /**
     * @throws RuntimeException
     */
    private function initializeDeckWithRetry(string $name): Deck
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRY_ATTEMPTS) {
            try {
                $deckData = $this->cardApi->initializeDeck($name);
                return Deck::fromArray($deckData);
            } catch (\Exception $e) {
                Log::warning('Deck initialization attempt failed with exception', [
                    'attempt' => $attempt + 1,
                    'error'   => $e->getMessage()
                ]);
                $lastException = $e;
            }

            $attempt++;
            if ($attempt < self::MAX_RETRY_ATTEMPTS) {
                usleep(self::RETRY_DELAY_MS * 1000);
            }
        }

        throw new RuntimeException(
            'Failed to initialize deck after ' . self::MAX_RETRY_ATTEMPTS . ' attempts: ' .
            $lastException?->getMessage()
        );
    }

    /**
     * @param array<string> $players
     * @return Collection<int, PlayerDto>
     */
    private function createPlayerDtos(array $players): Collection
    {
        return collect($players)->map(fn (string $playerName) => new PlayerDto(
            name: $playerName
        ));
    }

    private function createGameDto(string $name, Deck $deck, Collection $playerDtos): GameDto
    {
        return new GameDto(
            name: $name,
            deckSlug: $deck->slug(),
            players: $playerDtos
        );
    }
}
