<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'uploaded_by',
        'filename',
        'original_filename',
        'mime_type',
        'file_size',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeForHumansAttribute(): string
    {
        $bytes = floatval($this->file_size);
        $arBytes = [
            0 => [
                "UNIT"  => "TB",
                "VALUE" => pow(1024, 4)
            ],
            1 => [
                "UNIT"  => "GB",
                "VALUE" => pow(1024, 3)
            ],
            2 => [
                "UNIT"  => "MB",
                "VALUE" => pow(1024, 2)
            ],
            3 => [
                "UNIT"  => "KB",
                "VALUE" => 1024
            ],
            4 => [
                "UNIT"  => "B",
                "VALUE" => 1
            ],
        ];

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = round($result, 2) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result ?? '0 B';
    }
}
