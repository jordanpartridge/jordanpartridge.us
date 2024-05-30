<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['suit_id', 'rank', 'image'];

    /**
     * The suit of the card.
     *
     * @return BelongsTo
     */
    public function suit(): BelongsTo
    {
        return $this->belongsTo(Suit::class);
    }
}
