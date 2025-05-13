<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'gmail_message_id',
        'subject',
        'from',
        'to',
        'cc',
        'bcc',
        'snippet',
        'body',
        'label_ids',
        'raw_payload',
        'email_date',
        'synchronized_at',
    ];

    protected $casts = [
        'to'              => 'array',
        'cc'              => 'array',
        'bcc'             => 'array',
        'label_ids'       => 'array',
        'raw_payload'     => 'array',
        'email_date'      => 'datetime',
        'synchronized_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
