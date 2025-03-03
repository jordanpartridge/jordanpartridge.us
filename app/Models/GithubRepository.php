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
        'featured',
        'is_active',
        'display_order',
        'stars_count',
        'forks_count',
        'technologies',
        'last_fetched_at',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    protected function casts(): array
    {
        return [
            'last_fetched_at' => 'datetime:Y-m-d',
            'technologies'    => 'array',
        ];
    }
}
