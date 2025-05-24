<?php

namespace App\Console\Commands;

use App\Models\PerformanceMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupPerformanceMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:cleanup {--days=30 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old performance metrics data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning up performance metrics older than {$days} days...");

        $count = PerformanceMetric::where('created_at', '<', $cutoffDate)->count();

        if ($count > 0) {
            $this->info("Found {$count} records to delete.");

            if ($this->confirm('Do you want to proceed with deletion?')) {
                PerformanceMetric::where('created_at', '<', $cutoffDate)->delete();
                $this->info("Deleted {$count} old performance metric records.");
            } else {
                $this->info('Deletion cancelled.');
            }
        } else {
            $this->info('No old records found to delete.');
        }

        // Optimize the table
        $this->info('Optimizing performance_metrics table...');
        DB::statement('OPTIMIZE TABLE performance_metrics');
        $this->info('Table optimization complete.');
    }
}
