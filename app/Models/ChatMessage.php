<?php

namespace App\Models;

use Database\Factories\ChatMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    /**
     * @use HasFactory<ChatMessageFactory>
     */
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'content',
        'role',
    ];
}
