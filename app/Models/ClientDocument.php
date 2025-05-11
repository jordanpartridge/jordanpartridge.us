<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    public function getSignedUrlAttribute(): string
    {
        return Storage::disk('s3')->temporaryUrl(
            $this->filename,
            now()->addHours(1)
        );
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('client-documents.download', $this);
    }

    public function getDisplayIconAttribute(): string
    {
        return match ($this->mime_type) {
            'application/pdf' => 'heroicon-s-document text-red-500',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword' => 'heroicon-s-document-text text-blue-500',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'heroicon-s-document-chart-bar text-green-500',
            default                                                             => 'heroicon-s-document text-gray-400',
        };
    }
}
