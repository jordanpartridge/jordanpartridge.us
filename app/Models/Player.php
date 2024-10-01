<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;
    use HasSnowflakes;

    protected $fillable = [
        'name',
        'balance',
        'hand',
    ];

    protected $casts = [
        'id'      => Snowflake::class,
        'hand'    => 'array',
        'balance' => 'int',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
