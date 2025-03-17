<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\AI\AIContentService;
use Illuminate\Console\Command;

class TestAIContentGenerationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test-content {post_id? : The ID of the post to generate content for} {--platform=all : The platform to generate for (linkedin, twitter, all)}';

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
        $postId = $this->argument('post_id');
        $platform = $this->option('platform');
        
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
        
        if ($platform === 'all' || $platform === 'linkedin') {
            $this->generateForPlatform($aiService, $post, 'linkedin');
        }
        
        if ($platform === 'all' || $platform === 'twitter') {
            $this->generateForPlatform($aiService, $post, 'twitter');
        }
        
        // Generate summary
        $this->info("\nGenerating post summary:");
        $summary = $aiService->generateSummary($post);
        $this->line("----- Summary -----");
        $this->line($summary);
        
        return 0;
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
        }
    }
}
