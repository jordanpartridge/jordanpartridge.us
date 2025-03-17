<?php

namespace App\Services\AI;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Prism\Prism;

class AIContentService
{
    protected Prism $prism;
    
    /**
     * Create a new AI Content Service instance.
     */
    public function __construct(Prism $prism)
    {
        $this->prism = $prism;
    }
    
    /**
     * Generate social media content for a post.
     *
     * @param Post $post The blog post to generate content for
     * @param string $platform The social media platform (linkedin, twitter)
     * @return string The generated content
     */
    public function generateSocialPost(Post $post, string $platform = 'linkedin'): string
    {
        try {
            $prompt = $this->getPromptForPlatform($post, $platform);
            
            $response = $this->prism->run([
                'model' => config('services.prism.model', 'ollama/mistral:latest'),
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($platform)],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);
            
            Log::info('AI social media content generated', [
                'post_id' => $post->id,
                'platform' => $platform
            ]);
            
            return $response->content();
        } catch (\Exception $e) {
            Log::error('Error generating AI content', [
                'post_id' => $post->id,
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);
            
            return $this->getFallbackContent($post, $platform);
        }
    }
    
    /**
     * Get the appropriate system prompt based on the platform.
     *
     * @param string $platform
     * @return string
     */
    private function getSystemPrompt(string $platform): string
    {
        $prompts = [
            'linkedin' => "You are a professional content creator specializing in tech and software engineering content for LinkedIn. Your posts are insightful, professional, and engaging for a technical audience. Include appropriate hashtags at the end. Keep content between 180-220 words. Be concise but thoughtful.",
            'twitter' => "You are a tech-focused social media expert creating content for Twitter. Your posts are concise, engaging, and optimized for the platform. Include 2-3 relevant hashtags. Maximum length is 280 characters. Be impactful and conversational.",
        ];
        
        return $prompts[$platform] ?? $prompts['linkedin'];
    }
    
    /**
     * Get the user prompt for content generation.
     *
     * @param Post $post
     * @param string $platform
     * @return string
     */
    private function getPromptForPlatform(Post $post, string $platform): string
    {
        $excerpt = substr(strip_tags($post->content), 0, 300);
        
        return "Create a {$platform} post announcing my new blog article titled \"{$post->title}\". The post is about: {$post->description}. Here's an excerpt: \"{$excerpt}...\". Include a call to action to read the full article and use appropriate hashtags for software engineering, Laravel, and tech topics.";
    }
    
    /**
     * Generate a fallback content if AI generation fails.
     *
     * @param Post $post
     * @param string $platform
     * @return string
     */
    private function getFallbackContent(Post $post, string $platform): string
    {
        if ($platform === 'twitter') {
            return "I just published a new article: \"{$post->title}\". Check it out on my blog! #SoftwareEngineering #Laravel";
        }
        
        return "I'm excited to share my latest article: \"{$post->title}\". In this post, I explore {$post->description}. Read the full article on my blog. #SoftwareEngineering #Laravel #WebDevelopment";
    }
    
    /**
     * Generate a summary of a blog post.
     *
     * @param Post $post
     * @return string
     */
    public function generateSummary(Post $post): string
    {
        try {
            $content = substr(strip_tags($post->content), 0, 1000);
            
            $response = $this->prism->run([
                'model' => config('services.prism.model', 'ollama/mistral:latest'),
                'messages' => [
                    ['role' => 'system', 'content' => "You are an expert content summarizer. Create concise, engaging summaries that capture the key points of technical articles in 2-3 sentences."],
                    ['role' => 'user', 'content' => "Create a summary for this blog post titled '{$post->title}': {$content}"]
                ],
                'temperature' => 0.7,
                'max_tokens' => 150,
            ]);
            
            return $response->content();
        } catch (\Exception $e) {
            Log::error('Error generating AI summary', [
                'post_id' => $post->id,
                'error' => $e->getMessage()
            ]);
            
            return $post->description ?? substr(strip_tags($post->content), 0, 100) . '...';
        }
    }
}
