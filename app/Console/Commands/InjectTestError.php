<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class InjectTestError extends Command
{
    protected $signature = 'test:inject-error
                            {--type=database : Type of error to inject (database, memory, api)}
                            {--count=3 : Number of times to inject the error}';

    protected $description = 'Inject test errors for log monitoring system testing';

    public function handle()
    {
        $type = $this->option('type');
        $count = $this->option('count');

        $this->info("🧪 Injecting {$count} {$type} test errors...");

        for ($i = 1; $i <= $count; $i++) {
            $this->info("Injecting error {$i}/{$count}");

            switch ($type) {
                case 'database':
                    $this->injectDatabaseError();
                    break;
                case 'memory':
                    $this->injectMemoryError();
                    break;
                case 'api':
                    $this->injectApiError();
                    break;
                default:
                    $this->error("Unknown error type: {$type}");
                    return Command::FAILURE;
            }

            // Small delay between errors
            if ($i < $count) {
                sleep(2);
            }
        }

        $this->info("✅ Test errors injected. Check logs with: php artisan logs:monitor --use-ai --dry-run");

        return Command::SUCCESS;
    }

    protected function injectDatabaseError(): void
    {
        try {
            // Try to query a non-existent table
            DB::select('SELECT * FROM non_existent_test_table WHERE id = ?', [1]);
        } catch (\Exception $e) {
            // The error is automatically logged by Laravel
            $this->comment("Database error logged: " . substr($e->getMessage(), 0, 50) . '...');
        }
    }

    protected function injectMemoryError(): void
    {
        try {
            // Create a scenario that would cause memory issues in a real app
            $largeArray = [];
            for ($i = 0; $i < 1000000; $i++) {
                $largeArray[] = str_repeat('x', 1000);

                // Check memory and throw if it gets too high (simulate memory limit)
                if (memory_get_usage() > 50 * 1024 * 1024) { // 50MB limit for test
                    throw new Exception('Simulated memory limit exceeded during data processing');
                }
            }
        } catch (\Exception $e) {
            logger()->error('Memory processing error: ' . $e->getMessage(), [
                'memory_usage' => memory_get_usage(),
                'peak_memory'  => memory_get_peak_usage(),
                'operation'    => 'bulk_data_processing'
            ]);
            $this->comment("Memory error logged: " . substr($e->getMessage(), 0, 50) . '...');
        }
    }

    protected function injectApiError(): void
    {
        try {
            // Simulate an API call failure
            $response = file_get_contents('https://api.nonexistentservice.fake/data');
            if ($response === false) {
                throw new Exception('Failed to fetch data from external API');
            }
        } catch (\Exception $e) {
            logger()->error('External API integration failed', [
                'api_endpoint' => 'https://api.nonexistentservice.fake/data',
                'error'        => $e->getMessage(),
                'context'      => 'user_data_sync',
                'retry_count'  => 3
            ]);
            $this->comment("API error logged: " . substr($e->getMessage(), 0, 50) . '...');
        }
    }
}
