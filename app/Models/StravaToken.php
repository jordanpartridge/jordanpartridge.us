<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Laravel\Prompts\table;

class StravaToken extends Model
{
    use HasFactory;

    public $fillable = [
        'access_token',
        'expires_at',
        'refresh_token',
        'athlete_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * The user that the token belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * decrypt the access token after fetching it from the database
     */
    public function getAccessTokenAttribute(string $value): string
    {
        return decrypt($value);
    }

    /**
     * check if the token has expired
     */
    public function getExpiredAttribute(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * encrypt the access token before storing it in the database
     *
     * @param  string  $value
     */
    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    public function promptTable()
    {
        table(
            ['id', 'access_token', 'expires_at', 'refresh_token', 'athlete_id'],
            [
                [
                    $this->id,
                    $this->access_token,
                    $this->expires_at,
                    $this->refresh_token,
                    $this->athlete_id,
                ],
            ],
        );
    }
}
