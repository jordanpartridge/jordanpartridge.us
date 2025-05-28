<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateRealisticError extends Command
{
    protected $signature = 'demo:user-sync';
    protected $description = 'Sync user data (demo command that will cause realistic errors)';

    public function handle()
    {
        $this->info('Starting user synchronization...');

        try {
            // This will cause a realistic error - trying to access a property that doesn't exist
            $user = User::first();
            if ($user) {
                $profileData = $user->profile_settings->advanced_preferences;
                $this->info("Synced user profile data");
            }
        } catch (\Exception $e) {
            // This error will look like a real production bug
            logger()->error('User sync failed during profile processing', [
                'user_id'       => $user->id ?? null,
                'error'         => $e->getMessage(),
                'operation'     => 'profile_sync',
                'sync_batch_id' => uniqid('sync_'),
                'context'       => 'scheduled_user_sync'
            ]);

            $this->error('User sync failed: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
