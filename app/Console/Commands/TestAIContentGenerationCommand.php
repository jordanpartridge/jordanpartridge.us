<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\AI\AIContentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestAIContentGenerationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test-content
                            {post_id? : The ID of the post to generate content for}
                            {--platform=all : The platform to generate for (linkedin, twitter, all)}
                            {--type=all : Type of content to generate (social, summary, seo, all)}
                            {--seed : Seed the prompt templates database first}
                            {--debug : Enable debug logging for prompts and responses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the AI content generation service';

    /**
     * Execute the console command.
     */
    public function handle(AIContentService $aiService)
    {
        // Enable debug logging if requested
        if ($this->option('debug')) {
            config(['services.prism.log_prompts' => true]);
            config(['services.prism.log_responses' => true]);
            config(['services.prism.log_level' => 'debug']);
            $this->info('Debug logging enabled for AI service.');
        }

        // Seed the templates if requested
        if ($this->option('seed')) {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\PromptTemplateSeeder'
            ]);
            $this->info('Templates seeded successfully!');
        }

        $postId = $this->argument('post_id');
        $platform = $this->option('platform');
        $type = $this->option('type');

        // If no post ID provided, show most recent posts to select from
        if (!$postId) {
            $posts = Post::latest()->take(5)->get();

            if ($posts->isEmpty()) {
                $this->error('No posts found in the database');
                return 1;
            }

            $this->info('Recent posts:');
            $options = $posts->map(function ($post) {
                return $post->id . ': ' . $post->title;
            })->toArray();

            $selectedPostId = $this->choice('Select a post:', $options);
            $postId = explode(':', $selectedPostId)[0];
        }

        $post = Post::find($postId);

        if (!$post) {
            $this->error("Post with ID {$postId} not found");
            return 1;
        }

        $this->info("Generating AI content for post: {$post->title}");

        try {
            // Generate social content
            if ($type === 'all' || $type === 'social') {
                if ($platform === 'all' || $platform === 'linkedin') {
                    $this->generateForPlatform($aiService, $post, 'linkedin');
                }

                if ($platform === 'all' || $platform === 'twitter') {
                    $this->generateForPlatform($aiService, $post, 'twitter');
                }
            }

            // Generate summary
            if ($type === 'all' || $type === 'summary') {
                $this->info("\nGenerating post summary:");
                $summary = $aiService->generateSummary($post);
                $this->line("----- Summary -----");
                $this->line($summary);
                $this->line("----- End -----");
            }

            // Generate SEO metadata
            if ($type === 'all' || $type === 'seo') {
                $this->info("\nGenerating SEO metadata:");
                $metadata = $aiService->generateSeoMetadata($post);

                $this->line("----- SEO Metadata -----");
                $this->table(
                    ['Field', 'Value'],
                    [
                        ['Meta Title', $metadata['meta_title']],
                        ['Meta Description', $metadata['meta_description']],
                        ['Keywords', implode(', ', $metadata['keywords'])]
                    ]
                );
                $this->line("----- End -----");

                // Analyze metadata length
                $titleLength = strlen($metadata['meta_title']);
                $descLength = strlen($metadata['meta_description']);

                $this->info("Meta title: {$titleLength} characters " .
                    ($titleLength > 60 ? ($titleLength <= 65 ? '✓' : '⚠️ Too long') : '⚠️ Too short'));
                $this->info("Meta description: {$descLength} characters " .
                    ($descLength > 150 ? ($descLength <= 160 ? '✓' : '⚠️ Too long') : '⚠️ Too short'));
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error generating AI content: {$e->getMessage()}");
            Log::error('AI content generation test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }

    /**
     * Generate and display content for a specific platform.
     */
    protected function generateForPlatform(AIContentService $aiService, Post $post, string $platform): void
    {
        $this->info("\nGenerating {$platform} content:");
        $content = $aiService->generateSocialPost($post, $platform);

        $this->line("----- {$platform} Post -----");
        $this->line($content);
        $this->line("----- End -----");

        // Display character count for Twitter
        if ($platform === 'twitter') {
            $length = strlen($content);
            $this->info("Character count: {$length}/280");

            if ($length > 280) {
                $this->warn("Warning: Content exceeds Twitter's 280 character limit!");
            }
        } elseif ($platform === 'linkedin') {
            $length = strlen($content);
            $words = str_word_count($content);
            $this->info("Character count: {$length}, Word count: {$words}");

            if ($words < 80) {
                $this->warn("Warning: Content might be too short for LinkedIn (less than 80 words)");
            } elseif ($words > 210) {
                $this->warn("Warning: Content might be too long for optimal LinkedIn engagement (more than 210 words)");
            }
        }
    }
}
