<?php

use App\Models\PerformanceMetric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{actingAs};

uses(RefreshDatabase::class);

it('records performance metrics for requests', function () {
    $user = User::factory()->create();

    actingAs($user)->get('/');

    expect(PerformanceMetric::count())->toBeGreaterThan(0);

    $metric = PerformanceMetric::first();
    expect($metric)
        ->method->toBe('GET')
        ->response_time->toBeGreaterThan(0)
        ->response_status->toBe(200)
        ->user_id->toBe($user->id)
        ->memory_usage->toBeGreaterThan(0)
        ->db_queries->toBeGreaterThan(0);
});

it('filters slow requests correctly', function () {
    PerformanceMetric::factory()->create(['response_time' => 500]);
    PerformanceMetric::factory()->create(['response_time' => 1500]);
    PerformanceMetric::factory()->create(['response_time' => 2000]);

    $slowMetrics = PerformanceMetric::slow()->get();
    expect($slowMetrics)->toHaveCount(2);
});

it('filters recent metrics correctly', function () {
    PerformanceMetric::factory()->create(['created_at' => now()->subHours(12)]);
    PerformanceMetric::factory()->create(['created_at' => now()->subHours(36)]);

    $recentMetrics = PerformanceMetric::recent(24)->get();
    expect($recentMetrics)->toHaveCount(1);
});

it('calculates memory usage in MB correctly', function () {
    $metric = PerformanceMetric::factory()->create([
        'memory_usage' => 10 * 1024 * 1024, // 10MB in bytes
        'peak_memory'  => 15 * 1024 * 1024   // 15MB in bytes
    ]);

    expect($metric->memory_usage_in_mb)->toBe(10.0);
    expect($metric->peak_memory_in_mb)->toBe(15.0);
});

it('converts response time to seconds correctly', function () {
    $metric = PerformanceMetric::factory()->create(['response_time' => 1500]); // 1500ms

    expect($metric->response_time_in_seconds)->toBe(1.5);
});
