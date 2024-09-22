<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\BlackJackService;
use Illuminate\Console\Command;

use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class BlackJack extends Command
{
    protected $signature = 'play:blackjack';

    protected $description = 'Play a game of blackjack';

    public function handle(BlackJackService $blackJackService): void
    {
        $this->clearScreen();
        $this->displayTitle();

        $game = $blackJackService
            ->initializeGame(
                name: $this->promptForName(),
                players: $this->players(numberOfPlayers: $this->promptForNumPlayers())
            );

        $this->clearScreen();

        $deal = $blackJackService->deal(game: $game);

        $dealerCard = $deal['dealer'][0];

        $this->info('Dealer is showing: ' . $this->formatCard($dealerCard));

        // Add your game logic here
    }

    private function promptForName(): string
    {
        return text(
            label: 'What is the name of this game?',
            placeholder: 'BlackJack',
            validate: fn ($value) => Game::where('name', $value)->exists() ?
                'Game name already exists' : null
        );
    }

    private function promptForNumPlayers(): int
    {
        return suggest(
            'How many players?',
            ['1', '2', '3', '4', '5'],
            default: 1,
            required: true,
            validate: function ($value) {
                $intValue = (int) $value;
                if ($intValue < 1 || $intValue > 5) {
                    return 'Please select a number between 1 and 5.';
                }

                return null;
            }
        );
    }

    private function players(int $numberOfPlayers): array
    {
        $this->info('Great! Who is playing?');
        $players = [];

        foreach (range(1, $numberOfPlayers) as $player) {
            $players[] = text("Player $player name", "Player $player", required: true);
        }

        $this->info('Nice! So we have ' . implode(', ', $players) . ' playing!');

        return $players;
    }

    private function formatCard(array $card): string
    {
        $rank = str_pad($card['rank'], 2, ' ', STR_PAD_LEFT);
        $suit = $card['suit']['symbol'];
        $color = $card['suit']['name'] === 'Hearts' || $card['suit']['name'] === 'Diamonds' ? 'red' : 'black';

        return "
    <bg=white;fg=$color>            </>
    <bg=white;fg=$color>  $rank        </>
    <bg=white;fg=$color>            </>
    <bg=white;fg=$color>     $suit      </>
    <bg=white;fg=$color>            </>
    <bg=white;fg=$color>        $rank  </>
    <bg=white;fg=$color>            </>";
    }

    private function clearScreen(): void
    {
        $this->output->newLine(50);
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
