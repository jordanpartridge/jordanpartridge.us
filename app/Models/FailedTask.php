<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'platform',
        'method',
        'error',
        'attempts',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
