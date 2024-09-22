<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(array $array)
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'deck_slug'];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
