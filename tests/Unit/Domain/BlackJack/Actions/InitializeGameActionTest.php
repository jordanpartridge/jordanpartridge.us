<?php

use App\Contracts\CardServiceInterface;
use App\Domain\BlackJack\Actions\InitializeGameAction;
use App\Domain\BlackJack\DataTransferObjects\GameDto;
use App\Domain\BlackJack\Events\GameInitializedEvent;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    Log::shouldReceive('info')->byDefault();
    Log::shouldReceive('warning')->byDefault();
    Log::shouldReceive('error')->byDefault();
});

afterEach(function () {
    Mockery::close();
});

/**
 * Mock the GameInitializedEvent dispatch method
 */
function mockEventDispatch(): void
{
    // Create a mock for GameInitializedEvent that will capture the dispatch call
    $eventMock = Mockery::mock('alias:App\Domain\BlackJack\Events\GameInitializedEvent');
    $eventMock->shouldReceive('dispatch')->once()->andReturnUsing(function ($game) {
        // This just verifies the event was dispatched with a GameDto
        expect($game)->toBeInstanceOf(GameDto::class);
        return true;
    });
}

it('initializes a game successfully', function (): void {
    // Arrange
    mockEventDispatch();

    $cardService = Mockery::mock(CardServiceInterface::class);
    $cardService->shouldReceive('initializeDeck')
        ->once()
        ->with('test-game')
        ->andReturn([
            'slug'      => 'test-deck-123',
            'cards'     => [],
            'remaining' => 52
        ]);

    $action = new InitializeGameAction($cardService);

    // Act
    $result = $action->execute('test-game', ['player1', 'player2']);

    // Assert
    expect($result)
        ->toBeInstanceOf(GameDto::class)
        ->and($result->name)->toBe('test-game')
        ->and($result->deckSlug)->toBe('test-deck-123')
        ->and($result->players)->toHaveCount(2)
        ->and($result->players[0]->name)->toBe('player1')
        ->and($result->players[1]->name)->toBe('player2');
});

it('retries deck initialization on failure', function (): void {
    // Arrange
    mockEventDispatch();

    $cardService = Mockery::mock(CardServiceInterface::class);
    $cardService->shouldReceive('initializeDeck')
        ->once()
        ->with('test-game')
        ->andThrow(new RuntimeException('API error'));

    $cardService->shouldReceive('initializeDeck')
        ->once()
        ->with('test-game')
        ->andReturn([
            'slug'      => 'test-deck-123',
            'cards'     => [],
            'remaining' => 52
        ]);

    $action = new InitializeGameAction($cardService);

    // Act
    $result = $action->execute('test-game', ['player1']);

    // Assert
    expect($result)
        ->toBeInstanceOf(GameDto::class)
        ->and($result->deckSlug)->toBe('test-deck-123');
});

it('throws exception after max retry attempts', function (): void {
    // Arrange
    $cardService = Mockery::mock(CardServiceInterface::class);
    $cardService->shouldReceive('initializeDeck')
        ->times(3) // MAX_RETRY_ATTEMPTS is 3
        ->with('test-game')
        ->andThrow(new RuntimeException('API error'));

    $action = new InitializeGameAction($cardService);

    // Act & Assert
    expect(fn () => $action->execute('test-game', ['player1']))
        ->toThrow(RuntimeException::class, 'Failed to initialize deck after 3 attempts:');
});

it('validates game name', function (): void {
    // Arrange
    $cardService = Mockery::mock(CardServiceInterface::class);
    $action = new InitializeGameAction($cardService);

    // Act & Assert
    expect(fn () => $action->execute('', ['player1']))
        ->toThrow(InvalidArgumentException::class, 'Game name cannot be empty');
});

it('validates players array not empty', function (): void {
    // Arrange
    $cardService = Mockery::mock(CardServiceInterface::class);
    $action = new InitializeGameAction($cardService);

    // Act & Assert
    expect(fn () => $action->execute('test-game', []))
        ->toThrow(InvalidArgumentException::class, 'At least one player is required');
});

it('validates player names are non-empty strings', function (): void {
    // Arrange
    $cardService = Mockery::mock(CardServiceInterface::class);
    $action = new InitializeGameAction($cardService);

    // Act & Assert
    expect(fn () => $action->execute('test-game', ['player1', '']))
        ->toThrow(InvalidArgumentException::class, 'All player names must be non-empty strings');
});

it('logs errors when initialization fails', function (): void {
    // Arrange
    $cardService = Mockery::mock(CardServiceInterface::class);
    $cardService->shouldReceive('initializeDeck')
        ->times(3)
        ->andThrow(new RuntimeException('API error'));

    Log::shouldReceive('error')
        ->once()
        ->with('Failed to initialize game', Mockery::hasKey('error'));

    $action = new InitializeGameAction($cardService);

    // Act & Assert
    expect(fn () => $action->execute('test-game', ['player1']))
        ->toThrow(RuntimeException::class);
});
