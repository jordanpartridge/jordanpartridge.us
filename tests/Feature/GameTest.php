<?php

use App\Models\Game;
use App\Models\Player;
use Glhd\Bits\Snowflake;
use Illuminate\Support\Carbon;

it('can successfully be created with factory default settings.', function () {
    $game = Game::factory()->create();
    expect($game->game_id)->toBeInstanceOf(Snowflake::class)
        ->and($game->name)->toBeString()->not()->toBeEmpty()
        ->and($game->created_at)->toBeInstanceOf(Carbon::class)
        ->and($game->updated_at)->toBeInstanceOf(Carbon::class)
        ->and($game->deleted_at)->toBeNull();
});

it('belongs to many players (users).', function () {
    $game = Game::factory()->has(Player::factory(2), 'players')->create();
    expect($game->players()->count())->toBe(2)
        ->and($game->players()->first())->toBeInstanceOf(Player::class);
});
