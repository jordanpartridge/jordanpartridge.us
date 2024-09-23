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
    protected $description = 'Reset a user\'s password by email';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        activity('user:reset-password')->log('started');
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
        // Double check to make sure the user exists, in case the user was deleted since the search.
        if (! $user) {
            $this->error('User not found');

            return;
        }

        $newPassword = password(
            'New password for ' . $user->name,
            placeholder: 'New password',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 8 => 'The password must be at least 8 characters.',
                default            => null
            }
        );
        $user->password = bcrypt($newPassword);
        $user->save();
        activity('user:reset-password')->withProperties([
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ])->log('password reset');
        $this->info('Password reset for ' . $user->name);
    }
}
