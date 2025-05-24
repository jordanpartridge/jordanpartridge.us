<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class ShowSkillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'show:skills {--format=json : Output format}';

    /**
     * The console command description.
     */
    protected $description = 'Display Jordan\'s technical skills and expertise';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $skills = [
            'backend' => [
                'Laravel/PHP' => ['years' => 8, 'level' => 'Expert'],
                'Node.js' => ['years' => 5, 'level' => 'Advanced'],
                'Python' => ['years' => 4, 'level' => 'Intermediate'],
                'Go' => ['years' => 2, 'level' => 'Intermediate'],
            ],
            'frontend' => [
                'Vue.js' => ['years' => 6, 'level' => 'Expert'],
                'Alpine.js' => ['years' => 4, 'level' => 'Advanced'],
                'React' => ['years' => 3, 'level' => 'Intermediate'],
                'Tailwind CSS' => ['years' => 5, 'level' => 'Expert'],
            ],
            'databases' => [
                'MySQL' => ['years' => 8, 'level' => 'Expert'],
                'PostgreSQL' => ['years' => 5, 'level' => 'Advanced'],
                'Redis' => ['years' => 4, 'level' => 'Advanced'],
                'SQLite' => ['years' => 6, 'level' => 'Advanced'],
            ],
            'cloud_devops' => [
                'AWS' => ['years' => 6, 'level' => 'Advanced'],
                'Docker' => ['years' => 5, 'level' => 'Advanced'],
                'GitHub Actions' => ['years' => 4, 'level' => 'Advanced'],
                'Terraform' => ['years' => 3, 'level' => 'Intermediate'],
            ],
            'specialties' => [
                'API Development' => ['years' => 8, 'level' => 'Expert'],
                'Real-time Systems' => ['years' => 5, 'level' => 'Advanced'],
                'E-commerce' => ['years' => 6, 'level' => 'Expert'],
                'SaaS Architecture' => ['years' => 4, 'level' => 'Advanced'],
            ]
        ];

        if ($this->option('format') === 'json') {
            $this->line(json_encode($skills, JSON_PRETTY_PRINT));
            return self::SUCCESS;
        }

        $this->info('🚀 Jordan Partridge - Technical Skills & Expertise');
        $this->newLine();

        foreach ($skills as $category => $categorySkills) {
            $this->line('<fg=cyan>📂 ' . ucwords(str_replace('_', ' ', $category)) . '</fg=cyan>');
            
            foreach ($categorySkills as $skill => $details) {
                $level = match($details['level']) {
                    'Expert' => '<fg=green>●●●●●</fg=green>',
                    'Advanced' => '<fg=yellow>●●●●○</fg=yellow>',
                    'Intermediate' => '<fg=blue>●●●○○</fg=blue>',
                    default => '<fg=gray>●●○○○</fg=gray>'
                };
                
                $this->line("  <fg=white>{$skill}</fg=white> {$level} <fg=gray>({$details['years']} years)</fg=gray>");
            }
            $this->newLine();
        }

        $this->line('<fg=green>💡 Available for consultation and custom development projects!</fg=green>');
        $this->line('<fg=yellow>📧 Use `php artisan make:contact` to get in touch</fg=yellow>');

        return self::SUCCESS;
    }
}