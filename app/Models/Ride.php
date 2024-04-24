<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Ride extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'average_speed',
        'calories',
        'external_id',
        'moving_time',
        'elapsed_time',
        'elevation',
        'name',
        'polyline',
        'distance',
        'date',
        'max_speed',
    ];

    protected array $dates = [
        'date',
    ];

    public static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
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

    public function getDateAttribute(): string
    {
        return Carbon::create($this->attributes['date'])->format('M d, Y h:i A');
    }

    public function getElevationAttribute(): float
    {
        return Number::format($this->attributes['elevation'] * 3.28084, 1);
    }

    /**
     * @return string
     */
    public function getMovingTimeAttribute(): string
    {
        $hours = floor($this->attributes['moving_time'] / 3600);
        $minutes = floor(($this->attributes['moving_time'] % 3600) / 60);

        return $hours . 'h ' . $minutes . 'm';
    }
}
