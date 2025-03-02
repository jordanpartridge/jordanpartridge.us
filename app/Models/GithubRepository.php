<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubRepository extends Model
{
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    protected function casts(): array
    {
        return [
            'last_fetched_at' => 'datetime:Y-m-d',
        ];
    }
}
