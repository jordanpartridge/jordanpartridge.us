<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Console\Command;

class VerbsRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verbs:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback all models created by Verbs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()->delete();
        Player::query()->delete();
        Game::query()->delete();
    }
}
