<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class ComposerRequireCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'composer:require 
                           {package : Package name to require}
                           {--dev : Install as dev dependency}
                           {--simulate : Only simulate the installation}';

    /**
     * The console command description.
     */
    protected $description = 'Simulate installing composer packages (portfolio easter egg)';

    /**
     * Portfolio packages - simulated packages representing Jordan's skills
     */
    private array $portfolioPackages = [
        'jordanpartridge/expertise' => [
            'description' => 'Core expertise package with 8+ years of development experience',
            'skills' => ['Laravel', 'Vue.js', 'API Development', 'Database Design'],
            'version' => '^8.0'
        ],
        'jordanpartridge/leadership' => [
            'description' => 'Team leadership and project management capabilities',
            'skills' => ['Project Planning', 'Team Mentoring', 'Technical Architecture'],
            'version' => '^5.0'
        ],
        'jordanpartridge/innovation' => [
            'description' => 'Creative problem-solving and innovative solutions',
            'skills' => ['WebAssembly', 'Interactive Terminals', 'Modern UX'],
            'version' => '^2.0'
        ],
        'jordanpartridge/reliability' => [
            'description' => 'Military-grade reliability and attention to detail',
            'skills' => ['Code Quality', 'Testing', 'Documentation', 'Best Practices'],
            'version' => '^10.0'
        ],
        'jordanpartridge/communication' => [
            'description' => 'Clear technical communication and client relations',
            'skills' => ['Technical Writing', 'Client Consulting', 'Team Collaboration'],
            'version' => '^6.0'
        ],
        'jordanpartridge/strava-client' => [
            'description' => 'Real Laravel package for Strava API integration',
            'skills' => ['Package Development', 'API Clients', 'OAuth Implementation'],
            'version' => '^0.2',
            'real_package' => true,
            'packagist_url' => 'https://packagist.org/packages/jordanpartridge/strava-client'
        ]
    ];

    /**
     * Popular packages that Jordan works with
     */
    private array $realPackages = [
        'laravel/framework' => 'The Laravel Framework',
        'filament/filament' => 'Beautiful admin panels for Laravel',
        'spatie/laravel-permission' => 'Associate permissions with roles and users',
        'pestphp/pest' => 'Elegant PHP testing framework',
        'laravel/octane' => 'Supercharge your Laravel app performance',
        'livewire/livewire' => 'Build reactive Laravel components',
        'alpinejs/alpine' => 'Rugged, minimal frontend framework'
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $package = $this->argument('package');
        $isDev = $this->option('dev');
        $simulate = $this->option('simulate');

        $this->displayHeader();

        // Check if it's a portfolio package
        if (isset($this->portfolioPackages[$package])) {
            return $this->handlePortfolioPackage($package, $isDev, $simulate);
        }

        // Check if it's a real package Jordan uses
        if (isset($this->realPackages[$package])) {
            return $this->handleRealPackage($package, $isDev, $simulate);
        }

        // Handle generic package
        return $this->handleGenericPackage($package, $isDev, $simulate);
    }

    /**
     * Display header
     */
    private function displayHeader(): void
    {
        $this->line('<fg=blue>🎼 Composer - Portfolio Edition</fg=blue>');
        $this->line('<fg=gray>Requiring packages from Jordan\'s skill ecosystem...</fg=gray>');
        $this->newLine();
    }

    /**
     * Handle portfolio package installation
     */
    private function handlePortfolioPackage(string $package, bool $isDev, bool $simulate): int
    {
        $packageInfo = $this->portfolioPackages[$package];
        
        $this->line("<fg=yellow>📦 Installing portfolio package: {$package}</fg=yellow>");
        $this->newLine();

        // Show package info
        $this->line("<fg=cyan>Description:</fg=cyan> {$packageInfo['description']}");
        $this->line("<fg=cyan>Version:</fg=cyan> {$packageInfo['version']}");
        $this->line("<fg=cyan>Skills Included:</fg=cyan> " . implode(', ', $packageInfo['skills']));
        $this->newLine();

        if (!$simulate) {
            $this->simulateInstallation($package, $packageInfo['version']);
        }

        $this->displayInstallationSuccess($package, $packageInfo);

        // Special handling for real package
        if (isset($packageInfo['real_package']) && $packageInfo['real_package']) {
            $this->newLine();
            $this->line('<fg=green>🎉 This is actually a real package I created!</fg=green>');
            $this->line("<fg=blue>📦 Packagist: {$packageInfo['packagist_url']}</fg=blue>");
        }

        return self::SUCCESS;
    }

    /**
     * Handle real package installation
     */
    private function handleRealPackage(string $package, bool $isDev, bool $simulate): int
    {
        $description = $this->realPackages[$package];
        
        $this->line("<fg=yellow>📦 Installing package: {$package}</fg=yellow>");
        $this->line("<fg=gray>Description: {$description}</fg=gray>");
        $this->newLine();

        if (!$simulate) {
            $this->simulateInstallation($package, '^1.0');
        }

        $this->line('<fg=green>✅ Package installed successfully!</fg=green>');
        $this->line('<fg=blue>💡 This is a package I regularly use in my projects.</fg=blue>');

        return self::SUCCESS;
    }

    /**
     * Handle generic package installation
     */
    private function handleGenericPackage(string $package, bool $isDev, bool $simulate): int
    {
        $this->line("<fg=yellow>📦 Installing package: {$package}</fg=yellow>");
        $this->newLine();

        if (!$simulate) {
            $this->simulateInstallation($package, '^1.0');
        }

        $this->line('<fg=green>✅ Package installed successfully!</fg=green>');
        $this->newLine();

        $this->suggestPortfolioPackages();

        return self::SUCCESS;
    }

    /**
     * Simulate package installation process
     */
    private function simulateInstallation(string $package, string $version): void
    {
        $steps = [
            "Checking composer.json requirements...",
            "Resolving dependencies for {$package}...",
            "Downloading {$package} ({$version})...",
            "Installing {$package}...",
            "Generating autoload files...",
            "Updating composer.lock..."
        ];

        foreach ($steps as $step) {
            $this->line("<fg=blue>🔄 {$step}</fg=blue>");
            usleep(600000); // 0.6 seconds delay
        }

        $this->newLine();
    }

    /**
     * Display installation success for portfolio packages
     */
    private function displayInstallationSuccess(string $package, array $packageInfo): void
    {
        $this->line('<fg=green>✅ Portfolio package installed successfully!</fg=green>');
        $this->newLine();

        $this->line('<fg=cyan>📋 Package Contents:</fg=cyan>');
        foreach ($packageInfo['skills'] as $skill) {
            $this->line("   ✨ {$skill}");
        }
        $this->newLine();

        $this->line('<fg=yellow>🚀 Ready to use these skills in your project!</fg=yellow>');
        
        // Provide specific use cases based on package
        $useCases = $this->getUseCases($package);
        if (!empty($useCases)) {
            $this->line('<fg=blue>💡 Suggested use cases:</fg=blue>');
            foreach ($useCases as $useCase) {
                $this->line("   • {$useCase}");
            }
        }
    }

    /**
     * Get use cases for portfolio packages
     */
    private function getUseCases(string $package): array
    {
        return match($package) {
            'jordanpartridge/expertise' => [
                'Building scalable web applications',
                'API development and integration',
                'Database architecture and optimization',
                'Full-stack development projects'
            ],
            'jordanpartridge/leadership' => [
                'Leading development teams',
                'Technical project planning',
                'Code review and mentoring',
                'Architecture decision making'
            ],
            'jordanpartridge/innovation' => [
                'Experimental features and prototypes',
                'Creative user experience solutions',
                'Cutting-edge technology integration',
                'Interactive web applications'
            ],
            'jordanpartridge/reliability' => [
                'Mission-critical applications',
                'High-availability systems',
                'Quality assurance processes',
                'Production deployment strategies'
            ],
            'jordanpartridge/communication' => [
                'Client consultation and requirements gathering',
                'Technical documentation and training',
                'Cross-team collaboration',
                'Project stakeholder management'
            ],
            'jordanpartridge/strava-client' => [
                'Fitness app integrations',
                'Activity tracking features',
                'OAuth authentication flows',
                'Sports data analytics'
            ],
            default => []
        };
    }

    /**
     * Suggest portfolio packages
     */
    private function suggestPortfolioPackages(): void
    {
        $this->line('<fg=cyan>💡 Don\'t forget to check out Jordan\'s portfolio packages:</fg=cyan>');
        
        foreach ($this->portfolioPackages as $name => $info) {
            $realBadge = isset($info['real_package']) ? ' <fg=green>[REAL]</fg=green>' : '';
            $this->line("   📦 <fg=yellow>{$name}</fg=yellow>{$realBadge} - {$info['description']}");
        }

        $this->newLine();
        $this->line('<fg=blue>Try: composer require jordanpartridge/expertise --dev</fg=blue>');
    }
}