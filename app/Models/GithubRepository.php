<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubRepository extends Model
{
    protected $fillable = [
        'name',
        'repository',
        'description',
        'url',
        'technologies',
        'featured',
        'display_order',
        'is_active',
        'stars_count',
        'forks_count',
        'last_fetched_at',
    ];
    
    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'stars_count' => 'integer',
        'forks_count' => 'integer',
        'last_fetched_at' => 'datetime',
    ];
    
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function getFormattedLastFetchedAttribute()
    {
        return $this->last_fetched_at ? $this->last_fetched_at->diffForHumans() : 'Never';
    }
}
