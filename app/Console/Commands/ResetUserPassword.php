<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\suggest;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset user password';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = suggest(
            label: 'Select a user',
            options: fn (string $value) => User::where('email', 'like', '%' . $value . '%')
                ->get()
                ->pluck('email', 'id')->toArray(),
            placeholder: 'Search for a user',
            validate: fn (string $value) => match (true) {
                ! $value                                 => 'Please enter a value',
                ! User::where('email', $value)->exists() => 'User not found',
                default                                  => null,
            }
        );

        $user = User::where('email', $email)->first();
        // guess it could happen very unlikely but possible.
        if (! $user) {
            $this->error('User not found');

            return;
        }

        $newPassword = password('New password for ' . $user->name, placeholder: 'New password', required: true);
        $user->password = bcrypt($newPassword);
        $user->save();
        $this->info('Password reset for ' . $user->name);
    }
}
