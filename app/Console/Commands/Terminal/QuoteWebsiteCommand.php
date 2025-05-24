<?php

namespace App\Console\Commands\Terminal;

use Illuminate\Console\Command;

class QuoteWebsiteCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'quote:website 
                           {--pages=5 : Number of pages} 
                           {--features=* : Required features (auth,api,payments,cms,analytics)} 
                           {--type=business : Website type (business,ecommerce,saas,portfolio)}
                           {--budget= : Budget range (5k,10k,25k,50k+)}
                           {--timeline= : Preferred timeline (1month,3month,6month)}
                           {--format=interactive : Output format (interactive,json)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a custom website development quote';

    /**
     * Base pricing structure
     */
    private array $basePricing = [
        'business' => ['base' => 3000, 'per_page' => 300],
        'ecommerce' => ['base' => 8000, 'per_page' => 500],
        'saas' => ['base' => 15000, 'per_page' => 800],
        'portfolio' => ['base' => 2000, 'per_page' => 200],
    ];

    /**
     * Feature pricing
     */
    private array $featurePricing = [
        'auth' => ['price' => 1500, 'description' => 'User authentication & registration'],
        'api' => ['price' => 2500, 'description' => 'RESTful API development'],
        'payments' => ['price' => 3000, 'description' => 'Payment processing (Stripe/PayPal)'],
        'cms' => ['price' => 2000, 'description' => 'Content management system'],
        'analytics' => ['price' => 1000, 'description' => 'Analytics & reporting dashboard'],
        'search' => ['price' => 1500, 'description' => 'Advanced search functionality'],
        'notifications' => ['price' => 1200, 'description' => 'Email/SMS notifications'],
        'multi-tenant' => ['price' => 5000, 'description' => 'Multi-tenant architecture'],
        'real-time' => ['price' => 2000, 'description' => 'Real-time features (WebSockets)'],
        'mobile-app' => ['price' => 8000, 'description' => 'Mobile app (React Native)'],
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
     * Handle interactive quote generation
     */
    private function handleInteractive(): int
    {
        $this->displayHeader();

        // Gather requirements
        $type = $this->choice('What type of website do you need?', 
            ['business', 'ecommerce', 'saas', 'portfolio'], 
            $this->option('type') ?? 'business'
        );

        $pages = $this->ask('How many pages will your site have?', $this->option('pages') ?? 5);

        $this->line("\n<fg=cyan>Available features:</fg=cyan>");
        foreach ($this->featurePricing as $feature => $details) {
            $this->line("  <fg=yellow>{$feature}</fg=yellow> - {$details['description']} (+${$details['price']})");
        }

        $features = $this->choice(
            'Which features do you need? (comma-separated)', 
            array_keys($this->featurePricing),
            null,
            null,
            true
        );

        $timeline = $this->choice('Preferred timeline?', 
            ['1month', '3month', '6month'], 
            $this->option('timeline') ?? '3month'
        );

        // Calculate quote
        $quote = $this->calculateQuote($type, (int)$pages, $features, $timeline);

        // Display results
        $this->displayQuote($quote);

        return self::SUCCESS;
    }

    /**
     * Handle programmatic quote generation
     */
    private function handleProgrammatic(): int
    {
        $quote = $this->calculateQuote(
            $this->option('type') ?? 'business',
            (int)($this->option('pages') ?? 5),
            $this->option('features') ?? [],
            $this->option('timeline') ?? '3month'
        );

        if ($this->option('format') === 'json') {
            $this->line(json_encode($quote, JSON_PRETTY_PRINT));
        } else {
            $this->displayQuote($quote);
        }

        return self::SUCCESS;
    }

    /**
     * Calculate project quote
     */
    private function calculateQuote(string $type, int $pages, array $features, string $timeline): array
    {
        $basePrice = $this->basePricing[$type]['base'];
        $pagePrice = ($pages - 1) * $this->basePricing[$type]['per_page']; // First page included in base

        $featurePrice = 0;
        $selectedFeatures = [];

        foreach ($features as $feature) {
            if (isset($this->featurePricing[$feature])) {
                $featurePrice += $this->featurePricing[$feature]['price'];
                $selectedFeatures[$feature] = $this->featurePricing[$feature];
            }
        }

        $subtotal = $basePrice + $pagePrice + $featurePrice;

        // Timeline multipliers
        $timelineMultiplier = match($timeline) {
            '1month' => 1.3,  // Rush fee
            '3month' => 1.0,  // Standard
            '6month' => 0.9,  // Discount for flexible timeline
            default => 1.0
        };

        $total = $subtotal * $timelineMultiplier;

        return [
            'type' => $type,
            'pages' => $pages,
            'features' => $selectedFeatures,
            'timeline' => $timeline,
            'breakdown' => [
                'base_price' => $basePrice,
                'additional_pages' => $pagePrice,
                'features' => $featurePrice,
                'subtotal' => $subtotal,
                'timeline_adjustment' => $timelineMultiplier,
                'total' => round($total)
            ],
            'deliverables' => $this->getDeliverables($type, $features),
            'next_steps' => [
                'schedule_consultation' => 'php artisan consult:schedule',
                'refine_requirements' => 'We\'ll discuss your specific needs in detail',
                'proposal_delivery' => 'Detailed proposal within 2 business days'
            ]
        ];
    }

    /**
     * Get project deliverables
     */
    private function getDeliverables(string $type, array $features): array
    {
        $baseDeliverables = [
            'Custom responsive website design',
            'Mobile-optimized user experience',
            'Performance optimization',
            'Basic SEO setup',
            'SSL certificate setup',
            'Content migration assistance',
            '30 days of post-launch support'
        ];

        $typeSpecific = match($type) {
            'ecommerce' => ['Product catalog', 'Shopping cart', 'Inventory management'],
            'saas' => ['User dashboard', 'Subscription management', 'Admin panel'],
            'portfolio' => ['Project showcase', 'Contact forms', 'Blog platform'],
            default => ['Business information pages', 'Contact forms']
        };

        $featureSpecific = [];
        if (in_array('auth', $features)) $featureSpecific[] = 'User registration & login system';
        if (in_array('api', $features)) $featureSpecific[] = 'RESTful API with documentation';
        if (in_array('payments', $features)) $featureSpecific[] = 'Secure payment processing';
        if (in_array('cms', $features)) $featureSpecific[] = 'Content management interface';

        return array_merge($baseDeliverables, $typeSpecific, $featureSpecific);
    }

    /**
     * Display quote header
     */
    private function displayHeader(): void
    {
        $this->line('<fg=blue>');
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                    💰 PROJECT QUOTE GENERATOR                ║');
        $this->line('║              Get instant estimates for your website          ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->line('</fg=blue>');
        $this->newLine();
    }

    /**
     * Display formatted quote
     */
    private function displayQuote(array $quote): void
    {
        $this->newLine();
        $this->line('<fg=green>🎉 Your Custom Quote is Ready!</fg=green>');
        $this->newLine();

        $this->line("<fg=cyan>📋 Project Type:</fg=cyan> <fg=white>" . ucfirst($quote['type']) . " Website</fg=white>");
        $this->line("<fg=cyan>📄 Pages:</fg=cyan> <fg=white>{$quote['pages']} pages</fg=white>");
        $this->line("<fg=cyan>⏱️  Timeline:</fg=cyan> <fg=white>" . str_replace(['1month', '3month', '6month'], ['1 month (rush)', '3 months (standard)', '6 months (flexible)'], $quote['timeline']) . "</fg=white>");
        $this->newLine();

        $this->line('<fg=cyan>💰 Pricing Breakdown:</fg=cyan>');
        $breakdown = $quote['breakdown'];
        $this->line("   Base {$quote['type']} website: <fg=green>${$breakdown['base_price']}</fg=green>");
        
        if ($breakdown['additional_pages'] > 0) {
            $additionalPages = $quote['pages'] - 1;
            $this->line("   Additional pages ({$additionalPages}): <fg=green>+${$breakdown['additional_pages']}</fg=green>");
        }

        if (!empty($quote['features'])) {
            $this->line("   Features:");
            foreach ($quote['features'] as $feature => $details) {
                $this->line("     • {$details['description']}: <fg=green>+${$details['price']}</fg=green>");
            }
        }

        $this->line("   <fg=yellow>Subtotal: ${$breakdown['subtotal']}</fg=yellow>");
        
        if ($breakdown['timeline_adjustment'] != 1.0) {
            $adjustment = $breakdown['timeline_adjustment'] > 1 ? 'Rush fee' : 'Timeline discount';
            $this->line("   {$adjustment}: <fg=blue>" . round(($breakdown['timeline_adjustment'] - 1) * 100) . "%</fg=blue>");
        }

        $this->newLine();
        $this->line("<fg=green>🎯 Total Investment: $" . number_format($breakdown['total']) . "</fg=green>");
        $this->newLine();

        $this->line('<fg=cyan>📦 What You\'ll Get:</fg=cyan>');
        foreach ($quote['deliverables'] as $deliverable) {
            $this->line("   ✅ {$deliverable}");
        }

        $this->newLine();
        $this->line('<fg=yellow>🚀 Next Steps:</fg=yellow>');
        $this->line("   1. <fg=blue>php artisan consult:schedule</fg=blue> - Book a consultation call");
        $this->line("   2. We'll refine requirements and discuss your vision");
        $this->line("   3. Receive detailed proposal within 2 business days");
        $this->newLine();

        $this->line('<fg=green>💡 Ready to get started? Let\'s chat about bringing your vision to life!</fg=green>');
    }
}