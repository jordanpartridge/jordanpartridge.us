<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialShare extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'platform',
        'ip_address',
        'user_agent',
        'user_id',
        'post_id',
    ];

    /**
     * Get the post that was shared.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that shared the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include shares for a specific platform.
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope a query to only include shares for a specific URL.
     */
    public function scopeForUrl($query, string $url)
    {
        return $query->where('url', $url);
    }
}
