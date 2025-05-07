<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'user_id',
        'last_contact_at',
    ];

    protected $casts = [
        'status'          => ClientStatus::class,
        'last_contact_at' => 'date',
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
     */
    public function setWebsiteAttribute($value)
    {
        if ($value && is_string($value) && !empty(trim($value))) {
            if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                $this->attributes['website'] = 'https://' . $value;
            } else {
                $this->attributes['website'] = $value;
            }
        } else {
            $this->attributes['website'] = $value;
        }
    }
}
