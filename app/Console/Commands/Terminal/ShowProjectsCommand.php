<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class ShowProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'show:projects {--tech= : Filter by technology} {--type= : Filter by project type} {--format=table : Output format}';

    /**
     * The console command description.
     */
    protected $description = 'Display Jordan\'s portfolio projects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $projects = [
            [
                'name' => 'MyCareerAdvisor',
                'type' => 'career_platform',
                'description' => 'No-cost career services platform removing barriers for job seekers',
                'url' => 'https://www.mycareeradvisor.com',
                'github' => 'https://github.com/jordanpartridge/mycareeradvisor',
                'status' => 'Production',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'AWS'],
                'features' => ['User profiles', 'Job matching', 'Career resources', 'Application tracking'],
                'impact' => 'Helped 500+ job seekers find career opportunities'
            ],
            [
                'name' => 'JordanPartridge.us',
                'type' => 'personal_website',
                'description' => 'Personal portfolio and development blog with interactive terminal',
                'url' => 'https://www.jordanpartridge.us',
                'github' => 'https://github.com/jordanpartridge/jordanpartridge.us',
                'status' => 'Active Development',
                'technologies' => ['Laravel', 'Alpine.js', 'Tailwind CSS', 'WebAssembly'],
                'features' => ['Interactive terminal', 'Blog platform', 'Strava integration', 'Project showcase'],
                'impact' => 'Showcases innovative portfolio approach with browser terminal'
            ],
            [
                'name' => 'Strava Laravel Client',
                'type' => 'package',
                'description' => 'Laravel package for seamless Strava API integration',
                'url' => 'https://packagist.org/packages/jordanpartridge/strava-client',
                'github' => 'https://github.com/jordanpartridge/strava-client',
                'status' => 'Maintained',
                'technologies' => ['Laravel', 'PHP', 'Saloon HTTP', 'OAuth'],
                'features' => ['API client', 'OAuth flow', 'Activity sync', 'Webhook support'],
                'impact' => '1000+ downloads, used by cycling/fitness apps'
            ],
            [
                'name' => 'TaskFlow Pro',
                'type' => 'saas',
                'description' => 'Collaborative task management with automated workflows',
                'url' => 'https://taskflow.example.com',
                'github' => 'https://github.com/jordanpartridge/taskflow',
                'status' => 'Prototype',
                'technologies' => ['Laravel', 'Vue.js', 'PostgreSQL', 'Redis'],
                'features' => ['Real-time collaboration', 'Automated workflows', 'Analytics', 'API'],
                'impact' => 'Demo platform for showcasing SaaS architecture skills'
            ]
        ];

        // Apply filters
        if ($tech = $this->option('tech')) {
            $projects = array_filter($projects, function($project) use ($tech) {
                return in_array($tech, array_map('strtolower', $project['technologies'])) ||
                       in_array(ucfirst(strtolower($tech)), $project['technologies']);
            });
        }

        if ($type = $this->option('type')) {
            $projects = array_filter($projects, function($project) use ($type) {
                return $project['type'] === $type;
            });
        }

        if ($this->option('format') === 'json') {
            $this->line(json_encode(array_values($projects), JSON_PRETTY_PRINT));
            return self::SUCCESS;
        }

        $this->info('🚀 Jordan Partridge - Project Portfolio');
        $this->newLine();

        if (empty($projects)) {
            $this->warn('No projects found matching your criteria.');
            return self::SUCCESS;
        }

        foreach ($projects as $project) {
            $status_color = match($project['status']) {
                'Production' => 'green',
                'Active Development' => 'yellow',
                'Maintained' => 'blue',
                'Prototype' => 'magenta',
                default => 'gray'
            };

            $this->line("<fg=cyan>📁 {$project['name']}</fg=cyan> <fg={$status_color}>[{$project['status']}]</fg={$status_color}>");
            $this->line("   <fg=white>{$project['description']}</fg=white>");
            $this->line("   <fg=gray>🌐 {$project['url']}</fg=gray>");
            $this->line("   <fg=gray>📊 {$project['impact']}</fg=gray>");
            $this->line("   <fg=blue>🛠️  " . implode(', ', $project['technologies']) . "</fg=blue>");
            $this->newLine();
        }

        $this->line('<fg=green>💡 Interested in similar work? Use `php artisan quote:website` for project estimates!</fg=green>');

        return self::SUCCESS;
    }
}