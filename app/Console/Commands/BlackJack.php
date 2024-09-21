<?php

namespace App\Console\Commands;

use App\Services\CardService;
use Illuminate\Console\Command;

use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class BlackJack extends Command
{
    protected $signature = 'play:blackjack';

    protected $description = 'Play a game of blackjack';

    public function handle(CardService $cardService): void
    {
        $this->clearScreen();
        $this->displayTitle();

        $num_players = suggest('How many players?', ['1', '2', '3', '4', '5']);
        $players = $this->players($num_players);

        $this->clearScreen();

        $deck = $cardService->initializeDeck();
        $this->info('we have deck');

        $card = $cardService->drawCard();

        $this->info('we have a card: ' . $card[0]['rank'] . ' of ' . $card[0]['suit']);

        // Add your game logic here
    }

    private function players(int $num_players): array
    {
        $this->info('Great! Who is playing?');
        $players = [];

        foreach (range(1, $num_players) as $player) {
            $players[] = text("Player $player name", "Player $player");
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
