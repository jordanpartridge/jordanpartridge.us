<?php

namespace App\Models;

use Carbon\Carbon;
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
        'external_id',
        'name',
        'distance',
        'date',
        'max_speed',
    ];

    protected array $dates = [
        'date',
    ];

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
}
