<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Temporary implementation to stand in for the missing package trait.
 * This trait mimics the functionality of JordanPartridge\StravaClient\Concerns\HasStravaTokens
 * until the package dependency is properly resolved.
 */
trait HasStravaTokens
{
    /**
     * Get the Strava token for the user.
     */
    public function stravaToken(): HasOne
    {
        return $this->hasOne(\App\Models\StravaToken::class);
    }

    /**
     * Check if the user has a valid Strava token.
     */
    public function hasValidStravaToken(): bool
    {
        // Placeholder implementation
        return false;
    }
}
