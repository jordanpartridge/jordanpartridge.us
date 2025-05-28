<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync GitHub repositories daily
        $schedule->command('github:sync-repositories')
            ->daily()
            ->at('02:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->emailOutputOnFailure(config('mail.from.address'));

        // Cleanup old performance metrics daily
        $schedule->command('performance:cleanup --days=30')
            ->daily()
            ->at('03:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Automated log monitoring - check every 15 minutes
        $schedule->command('logs:monitor --interval=15 --threshold=3 --validate')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->emailOutputOnFailure(config('mail.from.address'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
