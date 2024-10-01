<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Game extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use LogsActivity;

    protected $casts = [
        'id' => Snowflake::class,
    ];

    protected $fillable = ['name', 'game_id'];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'deck_slug'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('system');
    }
}
