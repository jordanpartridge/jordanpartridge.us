<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmailToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the Gmail token.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token is expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
