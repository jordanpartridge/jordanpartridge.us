<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class AboutCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'about {--detailed : Show detailed information}';

    /**
     * The console command description.
     */
    protected $description = 'Display information about Jordan Partridge';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->displayAsciiArt();
        $this->newLine();
        
        $this->info('👨‍💻 Jordan Partridge - Full Stack Developer & Technical Consultant');
        $this->newLine();

        $this->line('<fg=cyan>📍 Location:</fg=cyan> <fg=white>Remote / Global</fg=white>');
        $this->line('<fg=cyan>💼 Experience:</fg=cyan> <fg=white>8+ years in web development</fg=white>');
        $this->line('<fg=cyan>🎯 Specialization:</fg=cyan> <fg=white>Laravel, Vue.js, SaaS Architecture</fg=white>');
        $this->line('<fg=cyan>🚴‍♂️ Passion:</fg=cyan> <fg=white>Cycling, Software Craftsmanship, Open Source</fg=white>');
        $this->newLine();

        if ($this->option('detailed')) {
            $this->displayDetailedInfo();
        }

        $this->line('<fg=green>🔗 Quick Links:</fg=green>');
        $this->line('   <fg=blue>📧</fg=blue> <fg=yellow>php artisan make:contact</fg=yellow> - Get in touch');
        $this->line('   <fg=blue>🛠️</fg=blue>  <fg=yellow>php artisan show:skills</fg=yellow> - View technical skills');
        $this->line('   <fg=blue>📁</fg=blue> <fg=yellow>php artisan show:projects</fg=yellow> - Browse portfolio');
        $this->line('   <fg=blue>💰</fg=blue> <fg=yellow>php artisan quote:website</fg=yellow> - Get project estimate');
        $this->newLine();

        $this->line('<fg=green>🎉 Welcome to my interactive terminal portfolio!</fg=green>');

        return self::SUCCESS;
    }

    /**
     * Display ASCII art banner
     */
    private function displayAsciiArt(): void
    {
        $art = "
    ╦╔═╗╦═╗╔╦╗╔═╗╔╗╔  ╔═╗╔═╗╦═╗╔╦╗╦═╗╦╔╦╗╔═╗╔═╗
    ║║ ║╠╦╝ ║║╠═╣║║║  ╠═╝╠═╣╠╦╝ ║ ╠╦╝║ ║║║ ╦║╣ 
 ╚═╝╚═╝╩╚══╩╝╩ ╩╝╚╝  ╩  ╩ ╩╩╚═ ╩ ╩╚═╩═╩╝╚═╝╚═╝
        ";

        $this->line('<fg=red>' . $art . '</fg=red>');
    }

    /**
     * Display detailed information
     */
    private function displayDetailedInfo(): void
    {
        $this->line('<fg=cyan>📚 Background:</fg=cyan>');
        $this->line('   • Senior Full Stack Developer with 8+ years of experience');
        $this->line('   • Expert in Laravel ecosystem and modern PHP development');
        $this->line('   • Passionate about clean code, testing, and software architecture');
        $this->line('   • Former Signal Corps soldier with attention to detail and reliability');
        $this->newLine();

        $this->line('<fg=cyan>🎖️ Military Service:</fg=cyan>');
        $this->line('   • U.S. Army Signal Corps - Communication Systems Specialist');
        $this->line('   • Developed skills in system reliability and mission-critical operations');
        $this->line('   • Transitioned technical military experience to civilian software development');
        $this->newLine();

        $this->line('<fg=cyan>🚴‍♂️ Beyond Code:</fg=cyan>');
        $this->line('   • Avid cyclist with 2000+ miles logged annually');
        $this->line('   • Strava integration enthusiast (built my own Laravel client!)');
        $this->line('   • Believes in work-life balance and outdoor adventures');
        $this->newLine();

        $this->line('<fg=cyan>🎯 Current Focus:</fg=cyan>');
        $this->line('   • Building innovative web applications with Laravel & Vue.js');
        $this->line('   • Creating reusable packages for the Laravel community');
        $this->line('   • Exploring WebAssembly and browser-based development tools');
        $this->line('   • Helping businesses scale with modern web technologies');
        $this->newLine();
    }
}