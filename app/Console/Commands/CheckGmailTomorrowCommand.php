<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckGmailTomorrowCommand extends Command
{
    protected $signature = 'gmail:check-tomorrow {email?}';
    protected $description = 'Check Gmail for tomorrow\'s activities';

    public function handle()
    {
        $email = $this->argument('email') ?: 'jordan@jordanpartridge.dev';

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email {$email} not found");
            return 1;
        }

        $this->info("Found user: {$user->name}");

        $tokens = $user->gmailAccounts;
        $this->info("Gmail accounts: {$tokens->count()}");

        if ($tokens->count() === 0) {
            $this->warn("No Gmail accounts connected");
            return 0;
        }

        foreach ($tokens as $token) {
            $this->info("Account: {$token->gmail_email} (Primary: " . ($token->is_primary ? 'Yes' : 'No') . ")");
            $this->info("Status: {$token->status}");

            if ($token->status !== 'connected') {
                $this->warn("Account not connected, skipping...");
                continue;
            }

            try {
                $gmailClient = $user->getGmailClientForAccount($token->gmail_email);

                if (!$gmailClient) {
                    $this->error("Could not get Gmail client for {$token->gmail_email}");
                    continue;
                }

                // Get tomorrow's date for filtering
                $tomorrow = now()->addDay()->format('Y/m/d');
                $this->info("Searching for emails about tomorrow ({$tomorrow})...");

                // Search for emails with various tomorrow-related queries
                $queries = [
                    'tomorrow',
                    'meeting tomorrow',
                    'due tomorrow',
                    'deadline tomorrow',
                    'appointment tomorrow',
                    'urgent',
                    'important',
                    'reminder'
                ];

                $foundSomething = false;

                foreach ($queries as $query) {
                    $this->info("\nSearching for: {$query}");

                    try {
                        $messages = $gmailClient->listMessages(['q' => $query, 'maxResults' => 3]);

                        if (is_countable($messages) && count($messages) > 0) {
                            $foundSomething = true;
                            $this->info("Found " . count($messages) . " messages:");

                            foreach ($messages as $message) {
                                try {
                                    // The message is an Email object from the Gmail client
                                    $subject = $message->subject ?? 'No Subject';
                                    $from = $message->from ?? 'Unknown Sender';

                                    $this->line("  📧 Subject: {$subject}");
                                    $this->line("     From: {$from}");
                                    $this->newLine();
                                } catch (\Exception $e) {
                                    $this->error("Error processing message: " . $e->getMessage());
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("Error searching for '{$query}': " . $e->getMessage());
                    }
                }

                if (!$foundSomething) {
                    $this->info("🎉 No urgent emails found! Looks like tomorrow might be a quiet day.");
                }

            } catch (\Exception $e) {
                $this->error("Error accessing Gmail: " . $e->getMessage());
            }
        }

        return 0;
    }
}
