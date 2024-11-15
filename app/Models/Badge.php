<?php

// app/Models/Badge.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public static function getActiveBadges()
    {
        return static::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(fn ($badge) => [
                'title' => $badge->title,
                'icon'  => $badge->icon,
            ]);
    }
}
