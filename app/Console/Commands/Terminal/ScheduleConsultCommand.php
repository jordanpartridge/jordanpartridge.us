<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class ScheduleConsultCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'consult:schedule 
                           {--duration=60 : Meeting duration in minutes (30,60,90)} 
                           {--topic= : Consultation topic}
                           {--timezone= : Your timezone}
                           {--format=interactive : Output format}';

    /**
     * The console command description.
     */
    protected $description = 'Schedule a consultation call with Jordan';

    /**
     * Available consultation types
     */
    private array $consultationTypes = [
        'project_consultation' => [
            'duration' => 60,
            'description' => 'Discuss your project requirements and get technical advice',
            'topics' => ['Requirements analysis', 'Technology recommendations', 'Architecture planning']
        ],
        'technical_review' => [
            'duration' => 45,
            'description' => 'Review existing codebase or technical approach',
            'topics' => ['Code review', 'Performance optimization', 'Security assessment']
        ],
        'career_mentoring' => [
            'duration' => 30,
            'description' => 'Career guidance for developers and technical professionals',
            'topics' => ['Career planning', 'Skill development', 'Industry insights']
        ],
        'quick_consultation' => [
            'duration' => 30,
            'description' => 'Quick technical discussion or question',
            'topics' => ['Technology questions', 'Quick advice', 'Problem solving']
        ]
    ];

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
     * Handle interactive consultation scheduling
     */
    private function handleInteractive(): int
    {
        $this->displayHeader();

        // Show available consultation types
        $this->line('<fg=cyan>📅 Available Consultation Types:</fg=cyan>');
        $this->newLine();

        foreach ($this->consultationTypes as $key => $type) {
            $this->line("<fg=yellow>🔹 " . ucwords(str_replace('_', ' ', $key)) . "</fg=yellow>");
            $this->line("   Duration: {$type['duration']} minutes");
            $this->line("   {$type['description']}");
            $this->line("   Topics: " . implode(', ', $type['topics']));
            $this->newLine();
        }

        // Get consultation type
        $consultationType = $this->choice(
            'What type of consultation do you need?',
            array_keys($this->consultationTypes)
        );

        // Get additional details
        $topic = $this->ask('Brief description of what you\'d like to discuss:');
        $timezone = $this->ask('What\'s your timezone?', 'UTC');
        
        $preferredTime = $this->choice(
            'Preferred time of day?',
            ['morning', 'afternoon', 'evening', 'flexible']
        );

        $urgency = $this->choice(
            'How soon do you need to schedule this?',
            ['this_week', 'next_week', 'within_month', 'flexible']
        );

        // Generate scheduling information
        $consultation = $this->generateConsultationDetails($consultationType, $topic, $timezone, $preferredTime, $urgency);

        $this->displayConsultationSummary($consultation);

        return self::SUCCESS;
    }

    /**
     * Handle programmatic consultation scheduling
     */
    private function handleProgrammatic(): int
    {
        $consultation = $this->generateConsultationDetails(
            'project_consultation',
            $this->option('topic') ?? 'General consultation',
            $this->option('timezone') ?? 'UTC',
            'flexible',
            'flexible'
        );

        if ($this->option('format') === 'json') {
            $this->line(json_encode($consultation, JSON_PRETTY_PRINT));
        } else {
            $this->displayConsultationSummary($consultation);
        }

        return self::SUCCESS;
    }

    /**
     * Generate consultation details
     */
    private function generateConsultationDetails(
        string $type, 
        string $topic, 
        string $timezone, 
        string $preferredTime, 
        string $urgency
    ): array {
        $consultationInfo = $this->consultationTypes[$type];
        
        return [
            'type' => $type,
            'duration' => $consultationInfo['duration'],
            'description' => $consultationInfo['description'],
            'topic' => $topic,
            'timezone' => $timezone,
            'preferred_time' => $preferredTime,
            'urgency' => $urgency,
            'calendar_link' => $this->generateCalendarLink($type),
            'preparation' => $this->getPreparationTips($type),
            'contact_info' => [
                'email' => 'jordan@jordanpartridge.us',
                'calendar' => 'https://calendly.com/jordanpartridge',
                'linkedin' => 'https://linkedin.com/in/jordan-partridge'
            ],
            'next_steps' => [
                '1. Click the calendar link below to choose your preferred time slot',
                '2. You\'ll receive a calendar invitation with meeting details',
                '3. Optional: Send a brief agenda or questions beforehand',
                '4. Join the call at the scheduled time (Zoom link will be provided)'
            ]
        ];
    }

    /**
     * Generate calendar booking link
     */
    private function generateCalendarLink(string $type): string
    {
        $baseUrl = 'https://calendly.com/jordanpartridge';
        
        return match($type) {
            'project_consultation' => "{$baseUrl}/project-consultation",
            'technical_review' => "{$baseUrl}/technical-review",
            'career_mentoring' => "{$baseUrl}/career-mentoring",
            'quick_consultation' => "{$baseUrl}/quick-consultation",
            default => "{$baseUrl}/consultation"
        };
    }

    /**
     * Get preparation tips for consultation type
     */
    private function getPreparationTips(string $type): array
    {
        return match($type) {
            'project_consultation' => [
                'Prepare a brief overview of your project goals',
                'List your technical requirements and constraints',
                'Think about your timeline and budget expectations',
                'Have any existing documentation or wireframes ready'
            ],
            'technical_review' => [
                'Prepare access to your codebase (GitHub, etc.)',
                'List specific areas of concern or questions',
                'Have performance metrics or issues documented',
                'Prepare examples of problematic code sections'
            ],
            'career_mentoring' => [
                'Think about your career goals and timeline',
                'Prepare questions about specific skills or technologies',
                'Have your current resume or portfolio ready',
                'Consider what challenges you\'re currently facing'
            ],
            'quick_consultation' => [
                'Prepare specific questions or problems to discuss',
                'Have relevant code snippets or documentation ready',
                'Think about what you\'ve already tried',
                'Be ready to share your screen if needed'
            ],
            default => [
                'Prepare an agenda or list of topics to discuss',
                'Think about your goals for the consultation',
                'Have any relevant materials ready to share'
            ]
        };
    }

    /**
     * Display consultation header
     */
    private function displayHeader(): void
    {
        $this->line('<fg=blue>');
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                   📅 CONSULTATION SCHEDULER                  ║');
        $this->line('║              Let\'s discuss your project or goals             ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->line('</fg=blue>');
        $this->newLine();
    }

    /**
     * Display consultation summary
     */
    private function displayConsultationSummary(array $consultation): void
    {
        $this->newLine();
        $this->line('<fg=green>🎉 Consultation Request Prepared!</fg=green>');
        $this->newLine();

        $this->line("<fg=cyan>📋 Consultation Type:</fg=cyan> <fg=white>" . ucwords(str_replace('_', ' ', $consultation['type'])) . "</fg=white>");
        $this->line("<fg=cyan>⏱️  Duration:</fg=cyan> <fg=white>{$consultation['duration']} minutes</fg=white>");
        $this->line("<fg=cyan>🌍 Timezone:</fg=cyan> <fg=white>{$consultation['timezone']}</fg=white>");
        $this->line("<fg=cyan>💬 Topic:</fg=cyan> <fg=white>{$consultation['topic']}</fg=white>");
        $this->newLine();

        $this->line('<fg=cyan>📝 How to Prepare:</fg=cyan>');
        foreach ($consultation['preparation'] as $tip) {
            $this->line("   ✅ {$tip}");
        }
        $this->newLine();

        $this->line('<fg=yellow>🚀 Next Steps:</fg=yellow>');
        foreach ($consultation['next_steps'] as $step) {
            $this->line("   {$step}");
        }
        $this->newLine();

        $this->line('<fg=green>🔗 Book Your Consultation:</fg=green>');
        $this->line("   📅 <fg=blue>{$consultation['calendar_link']}</fg=blue>");
        $this->newLine();

        $this->line('<fg=cyan>📞 Alternative Contact Methods:</fg=cyan>');
        $this->line("   📧 Email: <fg=yellow>{$consultation['contact_info']['email']}</fg=yellow>");
        $this->line("   💼 LinkedIn: <fg=blue>{$consultation['contact_info']['linkedin']}</fg=blue>");
        $this->newLine();

        $this->line('<fg=green>💡 Looking forward to our conversation!</fg=green>');
        $this->line('<fg=gray>   I typically respond to consultation requests within 24 hours.</fg=gray>');
    }
}