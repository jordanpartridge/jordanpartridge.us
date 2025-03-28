<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_type',
        'entity_type',
        'entity_id',
        'error',
        'context',
        'attempts',
    ];

    protected $casts = [
        'context'  => 'array',
        'attempts' => 'integer',
    ];

    /**
     * Get the related entity (post, etc.)
     */
    public function entity()
    {
        if ($this->entity_type === 'post') {
            return $this->belongsTo(Post::class, 'entity_id');
        }

        return null;
    }
}
