<?php

use App\Models\Ride;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can be created with factory', function () {
    $ride = Ride::factory()->create();
    expect($ride)->toBeInstanceOf(Ride::class);
});

it('has correct fillable attributes', function () {
    $fillable = [
        'average_speed', 'calories', 'date', 'external_id', 'moving_time',
        'elapsed_time', 'elevation', 'map_url', 'name', 'polyline',
        'distance', 'max_speed'
    ];
    expect(Ride::make()->getFillable())->toBe($fillable);
});

it('casts date attribute to datetime', function () {
    $ride = Ride::factory()->create();
    expect($ride->date)->toBeInstanceOf(Carbon::class);
});

it('orders rides by date in descending order', function () {
    Ride::factory()->create(['date' => now()->subDays(2)]);
    Ride::factory()->create(['date' => now()->subDay()]);
    Ride::factory()->create(['date' => now()]);

    $rides = Ride::all();
    expect($rides->first()->date)->toBeGreaterThan($rides->last()->date);
});

it('returns correct ride diff attribute', function () {
    $phoenixNow = now()->setTimezone('America/Phoenix');

    $rideTime = $phoenixNow->copy()->subHour();
    $ride = Ride::factory()->create(['date' => $rideTime]);
    expect($ride->ride_diff)->toBe('1 hour ago');
});

it('converts distance to miles', function () {
    $ride = Ride::factory()->create(['distance' => 1609.34]); // 1 mile in meters
    expect($ride->distance)->toBe(1.0);
});

it('converts max speed to mph', function () {
    $ride = Ride::factory()->create(['max_speed' => 4.4704]); // 10 mph in m/s
    expect($ride->max_speed)->toBe(10.0);
});

it('converts average speed to mph', function () {
    $ride = Ride::factory()->create(['average_speed' => 4.4704]); // 10 mph in m/s
    expect($ride->average_speed)->toBe(10.0);
});

it('converts elevation to feet', function () {
    $ride = Ride::factory()->create(['elevation' => 304.8]); // 1000 feet in meters
    expect($ride->elevation)->toBe('1,000.0');
});

it('formats moving time correctly', function () {
    $ride = Ride::factory()->create(['moving_time' => 3665]); // 1 hour 1 minute 5 seconds
    expect($ride->moving_time)->toBe('1h 1m');
});

it('sets date in America/Phoenix timezone', function () {
    $ride = Ride::factory()->create(['date' => '2023-01-01 12:00:00']);
    expect($ride->date->tzName)->toBe('America/Phoenix');
});
