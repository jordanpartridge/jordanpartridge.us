<?php

namespace Database\Factories;

use App\Models\PerformanceMetric;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerformanceMetric>
 */
class PerformanceMetricFactory extends Factory
{
    protected $model = PerformanceMetric::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $urls = [
            '/',
            '/blog',
            '/fat-bike-corps',
            '/contact',
            '/work-with-me',
            '/services',
            '/integrations/strava-client',
            '/integrations/gmail-client',
            '/admin/dashboard',
            '/admin/clients',
            '/admin/rides',
            '/admin/posts',
        ];

        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $statuses = array_merge(
            array_fill(0, 85, 200), // 85% success
            array_fill(0, 5, 404),   // 5% not found
            array_fill(0, 5, 302),   // 5% redirects
            array_fill(0, 3, 500),   // 3% server errors
            array_fill(0, 2, 422)    // 2% validation errors
        );

        $responseTime = $this->generateRealisticResponseTime();
        $dbQueries = $this->generateDbQueries();

        return [
            'url'             => $this->faker->randomElement($urls),
            'method'          => $this->faker->randomElement($methods),
            'response_time'   => $responseTime,
            'response_status' => $this->faker->randomElement($statuses),
            'memory_usage'    => $this->faker->numberBetween(10 * 1024 * 1024, 50 * 1024 * 1024), // 10-50MB
            'peak_memory'     => $this->faker->numberBetween(15 * 1024 * 1024, 60 * 1024 * 1024), // 15-60MB
            'cpu_usage'       => $this->faker->randomFloat(2, 0.1, 2.5),
            'db_queries'      => $dbQueries,
            'db_time'         => $dbQueries * $this->faker->numberBetween(1, 10), // 1-10ms per query
            'cache_hits'      => $this->faker->numberBetween(0, 20),
            'cache_misses'    => $this->faker->numberBetween(0, 5),
            'user_agent'      => $this->faker->userAgent(),
            'ip_address'      => $this->faker->ipv4(),
            'user_id'         => $this->faker->boolean(30) ? User::inRandomOrder()->first()?->id : null,
            'additional_data' => [
                'route'   => $this->faker->boolean(70) ? $this->faker->word() . '.index' : null,
                'ajax'    => $this->faker->boolean(20),
                'referer' => $this->faker->boolean(60) ? $this->faker->url() : null,
            ],
            'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ];
    }

    private function generateRealisticResponseTime(): int
    {
        // 70% fast (50-200ms), 20% normal (200-1000ms), 10% slow (1000-5000ms)
        $rand = rand(1, 100);
        if ($rand <= 70) {
            return $this->faker->numberBetween(50, 200);
        } elseif ($rand <= 90) {
            return $this->faker->numberBetween(200, 1000);
        } else {
            return $this->faker->numberBetween(1000, 5000);
        }
    }

    private function generateDbQueries(): int
    {
        // 50% light (1-5), 30% moderate (5-20), 20% heavy (20-100)
        $rand = rand(1, 100);
        if ($rand <= 50) {
            return $this->faker->numberBetween(1, 5);
        } elseif ($rand <= 80) {
            return $this->faker->numberBetween(5, 20);
        } else {
            return $this->faker->numberBetween(20, 100);
        }
    }
}
