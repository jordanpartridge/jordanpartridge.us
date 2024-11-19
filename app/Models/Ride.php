<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ride extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'average_speed',
        'calories',
        'date',
        'external_id',
        'moving_time',
        'elapsed_time',
        'elevation',
        'map_url',
        'name',
        'polyline',
        'distance',
        'max_speed',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }

    public function getMapUrlSignedAttribute(): string
    {
        if (!$this->map_url) {
            return '';
        }
        return Storage::disk('s3')->temporaryUrl($this->map_url, now()->addMinutes(5));
    }

    public function getRideDiffAttribute(): string
    {
        return $this->date->diffForHumans();
    }

    /**
     * Convert the distance from meters to miles, only take 1 decimal place append with miles
     */
    public function getDistanceAttribute(): float
    {
        return Number::format($this->attributes['distance'] * 0.000621371, 1);
    }

    public function getMaxSpeedAttribute(): float
    {
        return Number::format($this->attributes['max_speed'] * 2.23694, 1);
    }

    public function getAverageSpeedAttribute(): float
    {
        return Number::format($this->attributes['average_speed'] * 2.23694, 1);
    }

    public function getElevationAttribute(): false|string
    {
        return Number::format($this->attributes['elevation'] * 3.28084, 1);
    }

    public function getMovingTimeAttribute(): string
    {
        $hours = floor($this->attributes['moving_time'] / 3600);
        $minutes = floor(($this->attributes['moving_time'] % 3600) / 60);

        return $hours . 'h ' . $minutes . 'm';
    }

    public function getDateAttribute(): Carbon
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->attributes['date'],
            'America/Phoenix'
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('system');
    }
}
