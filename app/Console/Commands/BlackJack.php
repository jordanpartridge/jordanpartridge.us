<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\BlackJackService;
use Illuminate\Console\Command;
use JsonException;
use Laravel\Octane\Exceptions\DdException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class BlackJack extends Command
{
    protected $signature = 'play:blackjack';

    protected $description = 'Play a game of blackjack';

    /**
     * @throws DdException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function handle(BlackJackService $blackJackService): void
    {
        $this->clearScreen();
        $this->displayTitle();

        $name = $this->promptForName();
        $num_players = suggest('How many players?', ['1', '2', '3', '4', '5'], default: 1, required: true);
        $players = $this->players($num_players);

        $this->clearScreen();

        $game = $blackJackService->initializeGame($name, $players);

        // Add your game logic here
    }

    private function promptForName(): string
    {
        return text(
            label: 'What is the name of this game?',
            placeholder: 'BlackJack',
            default: 'BlackJack',
            validate: fn ($value) => Game::where('name', $value)->exists() ?
                'Game name already exists' : null
        );
    }

    private function players(int $num_players): array
    {
        $this->info('Great! Who is playing?');
        $players = [];

        foreach (range(1, $num_players) as $player) {
            $players[] = text("Player $player name", "Player $player", required: true);
        }

        $this->info('Nice! So we have ' . implode(', ', $players) . ' playing!');

        return $players;
    }

    private function clearScreen(): void
    {
        $this->output->newLine(50); // Add 50 new lines to simulate clearing the screen
    }

    private function displayTitle(): void
    {
        $title = "
        ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦
        ____  _        _    ____ _  __   _   _    ____ _  __
        | __ )| |      / \  / ___| |/ /  | | / \  / ___| |/ /
        |  _ \| |     / _ \| |   | ' /   | |/ _ \| |   | ' /
        | |_) | |___ / ___ \ |___| . \ \ | / ___ \ |___| . \
        |____/|_____/_/   \_\____|_|\_\ \/_/   \_\____|_|\_\

        ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦ ♠♥♣♦
        ";

        $this->line('<fg=green>' . $title . '</>');
        $this->info('<fg=yellow>Welcome to the Casino! Let\'s play Blackjack!</>');
    }
}
