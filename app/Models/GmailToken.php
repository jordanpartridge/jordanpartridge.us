<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmailToken extends Model
{
    protected $fillable = [
        'user_id',
        'gmail_email',
        'account_name',
        'is_primary',
        'access_token',
        'refresh_token',
        'expires_at',
        'last_sync_at',
        'account_info',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'last_sync_at' => 'datetime',
        'is_primary'   => 'boolean',
        'account_info' => 'array',
    ];

    /**
     * Get the user that owns the Gmail token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token is expired.
     *
     * @return bool True if the token is expired, false otherwise
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return true;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Check if this is the primary Gmail account for the user
     */
    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    /**
     * Set this account as the primary account (and unset others)
     */
    public function setPrimary(): void
    {
        // First, unset any other primary accounts for this user
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Then set this one as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Get display name for the account
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->account_name) {
            return $this->account_name;
        }

        if ($this->gmail_email) {
            return $this->gmail_email;
        }

        return 'Gmail Account';
    }

    /**
     * Get account status (connected, expired, etc.)
     */
    public function getStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->access_token) {
            return 'connected';
        }

        return 'disconnected';
    }

    /**
     * Get formatted last sync time
     */
    public function getLastSyncFormatAttribute(): ?string
    {
        return $this->last_sync_at?->diffForHumans();
    }

    /**
     * Get account avatar (Google profile picture or initials)
     */
    public function getAvatarAttribute(): string
    {
        // First try to get the profile picture from Google account info
        if ($this->account_info && isset($this->account_info['picture'])) {
            return $this->account_info['picture'];
        }

        // Fallback to initials if we have an email
        if ($this->gmail_email) {
            return strtoupper(substr($this->gmail_email, 0, 2));
        }

        // Default fallback
        return 'GM';
    }

    /**
     * Check if avatar is an image URL or initials
     */
    public function getIsAvatarImageAttribute(): bool
    {
        return $this->account_info && isset($this->account_info['picture']);
    }

    /**
     * Scope to get primary account for a user
     */
    public function scopePrimary($query, int $userId)
    {
        return $query->where('user_id', $userId)->where('is_primary', true);
    }

    /**
     * Scope to get active (non-expired) accounts
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
