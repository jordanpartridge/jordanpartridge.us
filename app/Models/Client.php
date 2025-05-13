<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 */
class Client extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'website',
        'notes',
        'status',
        'is_focused',
        'user_id',
        'last_contact_at',
    ];

    protected $casts = [
        'status'          => ClientStatus::class,
        'is_focused'      => 'boolean',
        'last_contact_at' => 'datetime',
    ];

    /**
     * Get the user that manages this client.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('client');
    }

    /**
     * Format the website URL before saving.
     * Empty strings are converted to null, and URLs without a protocol get https:// prefixed.
     */
    public function setWebsiteAttribute($value)
    {
        // Convert to null if empty string or only whitespace
        if ($value === '' || (is_string($value) && trim($value) === '')) {
            $this->attributes['website'] = null;
            return;
        }

        // Handle non-empty strings that need formatting
        if ($value && is_string($value) && !empty(trim($value))) {
            if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                $this->attributes['website'] = 'https://' . trim($value);
            } else {
                $this->attributes['website'] = trim($value);
            }
        } else {
            // For null or other values
            $this->attributes['website'] = $value;
        }
    }

    /**
     * Set this client as the focused client and remove focus from others.
     */
    public function focus(): self
    {
        // Begin a transaction to ensure atomicity
        DB::transaction(function () {
            // Remove focus from all clients
            static::query()->where('id', '!=', $this->id)->update(['is_focused' => false]);

            // Set this client as focused
            $this->is_focused = true;
            $this->save();
        });

        return $this;
    }

    /**
     * Remove focus from this client.
     */
    public function unfocus(): self
    {
        $this->is_focused = false;
        $this->save();

        return $this;
    }

    /**
     * Scope a query to only include the focused client.
     */
    public function scopeFocused($query)
    {
        return $query->where('is_focused', true);
    }

    /**
     * Get the documents for the client.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class);
    }

    /**
     * Get the emails for the client.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(ClientEmail::class);
    }
}
