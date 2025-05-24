<?php

namespace Database\Seeders;

use App\Models\PerformanceMetric;
use Illuminate\Database\Seeder;

class PerformanceMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Generating performance metrics data...');

        // Generate metrics for the last 7 days
        PerformanceMetric::factory()
            ->count(1000)
            ->create();

        $this->command->info('Generated 1000 performance metric records.');
    }
}
