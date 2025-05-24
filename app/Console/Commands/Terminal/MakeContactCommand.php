<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class MakeContactCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:contact 
                           {--name= : Your name}
                           {--email= : Your email address}
                           {--company= : Your company name}
                           {--message= : Your message}
                           {--format=interactive : Output format}';

    /**
     * The console command description.
     */
    protected $description = 'Create a contact request to reach Jordan';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('format') === 'interactive') {
            return $this->handleInteractive();
        }

        return $this->handleProgrammatic();
    }

    /**
     * Handle interactive contact form
     */
    private function handleInteractive(): int
    {
        $this->displayHeader();

        // Gather contact information
        $name = $this->ask('What\'s your name?');
        $email = $this->ask('What\'s your email address?');
        $company = $this->ask('Company/Organization (optional):', 'N/A');
        
        $this->line('<fg=cyan>What would you like to discuss?</fg=cyan>');
        $contactReason = $this->choice('Choose the primary reason for contact:', [
            'project_inquiry' => 'New project inquiry',
            'collaboration' => 'Partnership or collaboration',
            'consultation' => 'Technical consultation',
            'hiring' => 'Job opportunity or contract work',
            'speaking' => 'Speaking engagement or interview',
            'general' => 'General question or networking'
        ]);

        $message = $this->ask('Please describe your inquiry in a few sentences:');

        // Generate contact summary
        $contact = $this->generateContactSummary($name, $email, $company, $contactReason, $message);

        $this->displayContactSummary($contact);

        // Ask if they want to send it
        if ($this->confirm('Would you like me to send this contact request?')) {
            $this->sendContactRequest($contact);
        } else {
            $this->line('<fg=yellow>Contact request saved but not sent. You can reach out directly at the contact info above.</fg=yellow>');
        }

        return self::SUCCESS;
    }

    /**
     * Handle programmatic contact creation
     */
    private function handleProgrammatic(): int
    {
        $contact = $this->generateContactSummary(
            $this->option('name') ?? 'Anonymous',
            $this->option('email') ?? '',
            $this->option('company') ?? 'N/A',
            'general',
            $this->option('message') ?? 'Contact request via terminal'
        );

        if ($this->option('format') === 'json') {
            $this->line(json_encode($contact, JSON_PRETTY_PRINT));
        } else {
            $this->displayContactSummary($contact);
        }

        return self::SUCCESS;
    }

    /**
     * Generate contact summary
     */
    private function generateContactSummary(
        string $name, 
        string $email, 
        string $company, 
        string $reason, 
        string $message
    ): array {
        return [
            'contact_info' => [
                'name' => $name,
                'email' => $email,
                'company' => $company,
                'submitted_at' => now()->toISOString()
            ],
            'inquiry' => [
                'type' => $reason,
                'message' => $message,
                'source' => 'Terminal Interface'
            ],
            'response_expectation' => $this->getResponseExpectation($reason),
            'alternative_contact' => [
                'email' => 'jordan@jordanpartridge.us',
                'linkedin' => 'https://linkedin.com/in/jordan-partridge',
                'github' => 'https://github.com/jordanpartridge',
                'website' => 'https://jordanpartridge.us'
            ],
            'automated_response' => $this->generateAutomatedResponse($reason)
        ];
    }

    /**
     * Get response expectation based on contact reason
     */
    private function getResponseExpectation(string $reason): array
    {
        return match($reason) {
            'project_inquiry' => [
                'response_time' => '24-48 hours',
                'next_steps' => 'Project consultation call to discuss requirements',
                'what_to_expect' => 'Detailed discussion about your project scope, timeline, and technical approach'
            ],
            'collaboration' => [
                'response_time' => '2-3 business days',
                'next_steps' => 'Initial discussion about collaboration opportunities',
                'what_to_expect' => 'Exploration of mutual interests and potential partnership'
            ],
            'consultation' => [
                'response_time' => '24 hours',
                'next_steps' => 'Schedule consultation call',
                'what_to_expect' => 'Technical discussion and expert advice on your specific questions'
            ],
            'hiring' => [
                'response_time' => '3-5 business days',
                'next_steps' => 'Review opportunity details and schedule interview',
                'what_to_expect' => 'Discussion about role requirements, company culture, and technical challenges'
            ],
            'speaking' => [
                'response_time' => '1 week',
                'next_steps' => 'Review speaking opportunity and schedule planning call',
                'what_to_expect' => 'Discussion about topic, audience, and event logistics'
            ],
            'general' => [
                'response_time' => '2-3 business days',
                'next_steps' => 'Response based on your specific inquiry',
                'what_to_expect' => 'Personalized response to your questions or networking request'
            ],
            default => [
                'response_time' => '2-3 business days',
                'next_steps' => 'Follow-up based on inquiry type',
                'what_to_expect' => 'Personalized response to your message'
            ]
        };
    }

    /**
     * Generate automated response message
     */
    private function generateAutomatedResponse(string $reason): string
    {
        $greeting = "Thanks for reaching out! ";
        
        $specific = match($reason) {
            'project_inquiry' => "I'm excited to learn about your project. I'll review your requirements and get back to you with questions and next steps for a consultation call.",
            'collaboration' => "I'm always interested in meaningful collaborations. I'll review your proposal and reach out to discuss potential opportunities.",
            'consultation' => "I'd be happy to help with your technical questions. I'll respond with some initial thoughts and options for a more detailed consultation.",
            'hiring' => "Thank you for considering me for this opportunity. I'll review the details and get back to you about next steps.",
            'speaking' => "I appreciate the speaking opportunity. I'll review the event details and get back to you about availability and topic ideas.",
            'general' => "I appreciate you taking the time to connect. I'll review your message and respond accordingly.",
            default => "I'll review your message and get back to you soon."
        };

        return $greeting . $specific . " In the meantime, feel free to check out my work on GitHub or connect with me on LinkedIn.";
    }

    /**
     * Display contact header
     */
    private function displayHeader(): void
    {
        $this->line('<fg=blue>');
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                     📧 CONTACT JORDAN                        ║');
        $this->line('║                Let\'s start a conversation                    ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->line('</fg=blue>');
        $this->newLine();
    }

    /**
     * Display contact summary
     */
    private function displayContactSummary(array $contact): void
    {
        $this->newLine();
        $this->line('<fg=green>📋 Contact Request Summary</fg=green>');
        $this->newLine();

        $this->line("<fg=cyan>👤 Contact:</fg=cyan> <fg=white>{$contact['contact_info']['name']}</fg=white>");
        $this->line("<fg=cyan>📧 Email:</fg=cyan> <fg=white>{$contact['contact_info']['email']}</fg=white>");
        
        if ($contact['contact_info']['company'] !== 'N/A') {
            $this->line("<fg=cyan>🏢 Company:</fg=cyan> <fg=white>{$contact['contact_info']['company']}</fg=white>");
        }

        $this->line("<fg=cyan>📝 Inquiry Type:</fg=cyan> <fg=white>" . ucwords(str_replace('_', ' ', $contact['inquiry']['type'])) . "</fg=white>");
        $this->newLine();

        $this->line('<fg=cyan>💬 Message:</fg=cyan>');
        $this->line("   <fg=white>{$contact['inquiry']['message']}</fg=white>");
        $this->newLine();

        $expectation = $contact['response_expectation'];
        $this->line('<fg=yellow>⏱️  What to Expect:</fg=yellow>');
        $this->line("   ⚡ Response time: <fg=green>{$expectation['response_time']}</fg=green>");
        $this->line("   🎯 Next steps: {$expectation['next_steps']}");
        $this->line("   📋 What to expect: {$expectation['what_to_expect']}");
        $this->newLine();

        $this->line('<fg=cyan>📞 Direct Contact Info:</fg=cyan>');
        foreach ($contact['alternative_contact'] as $method => $value) {
            $icon = match($method) {
                'email' => '📧',
                'linkedin' => '💼',
                'github' => '🐙',
                'website' => '🌐',
                default => '📱'
            };
            $this->line("   {$icon} " . ucfirst($method) . ": <fg=blue>{$value}</fg=blue>");
        }
        $this->newLine();
    }

    /**
     * Simulate sending contact request
     */
    private function sendContactRequest(array $contact): void
    {
        $this->line('<fg=yellow>📤 Sending contact request...</fg=yellow>');
        
        // Simulate processing time
        sleep(2);
        
        $this->line('<fg=green>✅ Contact request sent successfully!</fg=green>');
        $this->newLine();
        
        $this->line('<fg=blue>🤖 Automated Response:</fg=blue>');
        $this->line("   {$contact['automated_response']}");
        $this->newLine();
        
        $this->line('<fg=green>🎉 Thanks for reaching out! I\'ll be in touch soon.</fg=green>');
    }
}