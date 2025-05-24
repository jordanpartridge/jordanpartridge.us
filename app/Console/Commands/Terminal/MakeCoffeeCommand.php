<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class MakeCoffeeCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:coffee 
                           {--strength=medium : Coffee strength (weak,medium,strong,espresso)} 
                           {--size=large : Cup size (small,medium,large,xl)}
                           {--type=drip : Coffee type (drip,espresso,french-press,cold-brew)}';

    /**
     * The console command description.
     */
    protected $description = 'Brew the perfect cup of coffee for coding sessions';

    /**
     * Coffee ASCII art
     */
    private array $coffeeArt = [
        'small' => [
            '     (',
            '      )',
            '  ________',
            ' |        |]',
            ' \        /',
            '  `------´'
        ],
        'large' => [
            '       (',
            '        )',
            '   ____________',
            '  |            |]',
            '  |  ☕ FUEL   |]',
            '  |    FOR     |]',
            '  |   CODING   |]',
            '  \____________/',
            '   `----------´'
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $strength = $this->option('strength');
        $size = $this->option('size');
        $type = $this->option('type');

        $this->displayHeader();
        $this->brewCoffee($strength, $size, $type);
        $this->displayCoffee($size);
        $this->displayCoffeeStats($strength, $size, $type);
        $this->displayMotivationalMessage();

        return self::SUCCESS;
    }

    /**
     * Display brewing header
     */
    private function displayHeader(): void
    {
        $this->line('<fg=yellow>☕ Jordan\'s Automated Coffee Machine v2.0</fg=yellow>');
        $this->line('<fg=gray>Because great code requires great coffee...</fg=gray>');
        $this->newLine();
    }

    /**
     * Simulate coffee brewing process
     */
    private function brewCoffee(string $strength, string $size, string $type): void
    {
        $steps = [
            'Grinding fresh coffee beans...',
            'Heating water to optimal temperature...',
            'Brewing your ' . $type . '...',
            'Adding the perfect amount of ' . $strength . ' extraction...',
            'Finalizing your ' . $size . ' cup...'
        ];

        foreach ($steps as $step) {
            $this->line("<fg=blue>🔄 {$step}</fg=blue>");
            usleep(800000); // 0.8 seconds delay
        }

        $this->newLine();
        $this->line('<fg=green>✅ Coffee brewing complete!</fg=green>');
        $this->newLine();
    }

    /**
     * Display coffee ASCII art
     */
    private function displayCoffee(string $size): void
    {
        $art = $size === 'small' ? $this->coffeeArt['small'] : $this->coffeeArt['large'];
        
        foreach ($art as $line) {
            $this->line('<fg=yellow>' . $line . '</fg=yellow>');
        }
        $this->newLine();
    }

    /**
     * Display coffee statistics
     */
    private function displayCoffeeStats(string $strength, string $size, string $type): void
    {
        $caffeine = $this->calculateCaffeine($strength, $size, $type);
        $productivity = $this->calculateProductivity($strength, $caffeine);
        $bugFixing = $this->calculateBugFixingPower($strength, $type);

        $this->line('<fg=cyan>📊 Coffee Statistics:</fg=cyan>');
        $this->line("   ☕ Type: " . ucwords(str_replace('-', ' ', $type)));
        $this->line("   💪 Strength: " . ucfirst($strength));
        $this->line("   📏 Size: " . ucfirst($size));
        $this->line("   ⚡ Caffeine: {$caffeine}mg");
        $this->line("   🚀 Productivity Boost: +{$productivity}%");
        $this->line("   🐛 Bug Fixing Power: {$bugFixing}/10");
        $this->newLine();

        $this->displayCaffeineEffects($caffeine);
    }

    /**
     * Calculate caffeine content
     */
    private function calculateCaffeine(string $strength, string $size, string $type): int
    {
        $baseCaffeine = match($type) {
            'espresso' => 64,
            'drip' => 95,
            'french-press' => 107,
            'cold-brew' => 200,
            default => 95
        };

        $strengthMultiplier = match($strength) {
            'weak' => 0.7,
            'medium' => 1.0,
            'strong' => 1.3,
            'espresso' => 1.5,
            default => 1.0
        };

        $sizeMultiplier = match($size) {
            'small' => 0.8,
            'medium' => 1.0,
            'large' => 1.4,
            'xl' => 1.8,
            default => 1.0
        };

        return round($baseCaffeine * $strengthMultiplier * $sizeMultiplier);
    }

    /**
     * Calculate productivity boost
     */
    private function calculateProductivity(string $strength, int $caffeine): int
    {
        $base = match($strength) {
            'weak' => 15,
            'medium' => 25,
            'strong' => 40,
            'espresso' => 55,
            default => 25
        };

        // Cap at reasonable productivity boost
        return min($base + ($caffeine / 10), 100);
    }

    /**
     * Calculate bug fixing power
     */
    private function calculateBugFixingPower(string $strength, string $type): int
    {
        $power = match($strength) {
            'weak' => 4,
            'medium' => 6,
            'strong' => 8,
            'espresso' => 10,
            default => 6
        };

        // Espresso gives extra bug fixing power
        if ($type === 'espresso') {
            $power = min($power + 1, 10);
        }

        return $power;
    }

    /**
     * Display caffeine effects
     */
    private function displayCaffeineEffects(int $caffeine): void
    {
        $this->line('<fg=yellow>🧠 Expected Effects:</fg=yellow>');

        if ($caffeine < 50) {
            $this->line('   😌 Mild alertness - Good for documentation writing');
            $this->line('   📝 Enhanced focus for planning and design work');
        } elseif ($caffeine < 100) {
            $this->line('   😊 Increased alertness - Perfect for regular coding');
            $this->line('   💻 Improved concentration and typing speed');
        } elseif ($caffeine < 200) {
            $this->line('   😎 High alertness - Ideal for complex problem solving');
            $this->line('   🔥 Enhanced debugging and algorithmic thinking');
        } else {
            $this->line('   🤯 Maximum alertness - Ready for all-night coding sessions!');
            $this->line('   ⚡ Peak performance for critical bug fixes');
            $this->line('   🚨 Warning: May cause urge to refactor everything');
        }

        $this->newLine();
    }

    /**
     * Display motivational message
     */
    private function displayMotivationalMessage(): void
    {
        $messages = [
            'Time to write some beautiful code! ✨',
            'Let\'s turn caffeine into clean, efficient code! 🚀',
            'Ready to tackle those challenging problems! 💪',
            'May your functions be pure and your bugs be few! 🐛',
            'Coffee loaded. Compiler ready. Let\'s build something amazing! 🏗️',
            'Caffeine level: Optimal. Creativity: Unlocked. 🎨',
            'Remember: Good code is like good coffee - it takes time to perfect! ⏰'
        ];

        $randomMessage = $messages[array_rand($messages)];
        
        $this->line('<fg=green>💡 ' . $randomMessage . '</fg=green>');
        $this->newLine();

        $this->line('<fg=gray>Pro tip: Pair this coffee with `php artisan show:projects` to review your work!</fg=gray>');
    }
}