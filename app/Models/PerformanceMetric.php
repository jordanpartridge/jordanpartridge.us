<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'method',
        'response_time',
        'response_status',
        'memory_usage',
        'peak_memory',
        'cpu_usage',
        'db_queries',
        'db_time',
        'cache_hits',
        'cache_misses',
        'user_agent',
        'ip_address',
        'user_id',
        'additional_data',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'cpu_usage'       => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeForUrl($query, $url)
    {
        return $query->where('url', $url);
    }

    public function scopeSlow($query, $threshold = 1000)
    {
        return $query->where('response_time', '>', $threshold);
    }

    public function getResponseTimeInSecondsAttribute(): float
    {
        return $this->response_time / 1000;
    }

    public function getMemoryUsageInMbAttribute(): float
    {
        return round($this->memory_usage / 1024 / 1024, 2);
    }

    public function getPeakMemoryInMbAttribute(): float
    {
        return round($this->peak_memory / 1024 / 1024, 2);
    }
}
