<?php

use App\Models\Player;
use Glhd\Bits\Snowflake;

it('can be created', function () {
    $player = Player::factory()->create();
    expect($player)->not->toBeNull()
        ->and($player->id)->toBeInstanceOf(Snowflake::class);
});
