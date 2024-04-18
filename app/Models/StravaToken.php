<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StravaToken extends Model
{
    public $fillable = [
        'access_token',
        'expires_at',
        'refresh_token',
        'athlete_id',
    ];

    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

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
}
