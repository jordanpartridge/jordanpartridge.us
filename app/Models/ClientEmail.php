<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Client Email Model
 *
 * Represents emails associated with clients, synchronized from Gmail API.
 * Stores email metadata and content for client communication tracking.
 */
class ClientEmail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'to'              => 'array',
        'cc'              => 'array',
        'bcc'             => 'array',
        'label_ids'       => 'array',
        'raw_payload'     => 'array',
        'email_date'      => 'datetime',
        'synchronized_at' => 'datetime',
    ];

    /**
     * Get the client that owns the email.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get a formatted list of recipients.
     *
     * @return string The recipients as a comma-separated string
     */
    public function getRecipientsAttribute(): string
    {
        $recipients = $this->to ?? [];
        return is_array($recipients) ? implode(', ', $recipients) : $recipients;
    }

    /**
     * Get an excerpt of the email body.
     *
     * @param int $length Maximum length of the excerpt
     * @return string The email body excerpt
     */
    public function getExcerpt(int $length = 100): string
    {
        if (empty($this->body)) {
            return $this->snippet ?? '';
        }

        $text = strip_tags($this->body);
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '...';
    }
}
