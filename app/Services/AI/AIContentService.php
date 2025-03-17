<?php

namespace App\Services\AI;

use App\Models\Post;
use App\Models\PromptTemplate;
use Exception;
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
            // Start logging with context
            $context = [
                'post_id'  => $post->id,
                'platform' => $platform,
                'action'   => 'generate_social_post'
            ];

            // Get template from database or use default
            $template = PromptTemplate::findByKey('social_post', $platform);

            if ($template) {
                Log::debug('Using database template for social post', $context + ['template_id' => $template->id]);

                // Format the template with variables
                $excerpt = substr(strip_tags($post->content), 0, 300);
                $formatted = $template->format([
                    'title'       => $post->title,
                    'description' => $post->description,
                    'excerpt'     => $excerpt,
                    'platform'    => $platform,
                ]);

                $systemPrompt = $formatted['system_prompt'];
                $userPrompt = $formatted['user_prompt'];
                $parameters = $formatted['parameters'] ?: $this->getDefaultParameters();
            } else {
                Log::debug('Using default template for social post', $context);

                // Use the legacy hard-coded prompts
                $systemPrompt = $this->getSystemPrompt($platform);
                $userPrompt = $this->getPromptForPlatform($post, $platform);
                $parameters = $this->getDefaultParameters();
            }

            // Log prompt details if configured
            if (config('services.prism.log_prompts', false)) {
                Log::debug('Prompt details', $context + [
                    'system_prompt' => $systemPrompt,
                    'user_prompt'   => $userPrompt,
                    'parameters'    => $parameters
                ]);
            }

            // Make the API request
            $response = $this->prism->run([
                'model'    => $parameters['model'] ?? config('services.prism.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => $parameters['temperature'] ?? config('services.prism.default_temperature', 0.7),
                'max_tokens'  => $parameters['max_tokens'] ?? config('services.prism.default_max_tokens', 500),
            ]);

            $content = $response->content();

            // Log response details if configured
            if (config('services.prism.log_responses', false)) {
                Log::debug('AI response', $context + [
                    'response' => $content
                ]);
            }

            Log::info('AI social media content generated successfully', $context);

            return $content;
        } catch (\Exception $e) {
            // Enhanced error logging
            Log::error('Error generating AI content', [
                'post_id'       => $post->id,
                'platform'      => $platform,
                'error_message' => $e->getMessage(),
                'error_code'    => $e->getCode(),
                'error_file'    => $e->getFile(),
                'error_line'    => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            // Try fallback model if available
            try {
                if (config('services.prism.fallback_model')) {
                    Log::info('Attempting to use fallback model', [
                        'post_id'        => $post->id,
                        'platform'       => $platform,
                        'fallback_model' => config('services.prism.fallback_model')
                    ]);

                    $response = $this->prism->run([
                        'model'    => config('services.prism.fallback_model'),
                        'messages' => [
                            ['role' => 'system', 'content' => $this->getSystemPrompt($platform)],
                            ['role' => 'user', 'content' => $this->getPromptForPlatform($post, $platform)]
                        ],
                        'temperature' => 0.7,
                        'max_tokens'  => 500,
                    ]);

                    Log::info('Successfully generated content with fallback model', [
                        'post_id'  => $post->id,
                        'platform' => $platform
                    ]);

                    return $response->content();
                }
            } catch (\Exception $fallbackException) {
                Log::error('Fallback model also failed', [
                    'post_id'  => $post->id,
                    'platform' => $platform,
                    'error'    => $fallbackException->getMessage()
                ]);
            }

            return $this->getFallbackContent($post, $platform);
        }
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
            $context = [
                'post_id' => $post->id,
                'action'  => 'generate_summary'
            ];

            // Get template from database or use default
            $template = PromptTemplate::findByKey('post_summary');
            $content = substr(strip_tags($post->content), 0, 1000);

            if ($template) {
                Log::debug('Using database template for post summary', $context + ['template_id' => $template->id]);

                // Format the template with variables
                $formatted = $template->format([
                    'title'   => $post->title,
                    'content' => $content,
                ]);

                $systemPrompt = $formatted['system_prompt'];
                $userPrompt = $formatted['user_prompt'];
                $parameters = $formatted['parameters'] ?: $this->getDefaultParameters();

                // Override max_tokens for summaries
                $parameters['max_tokens'] = $parameters['max_tokens'] ?? 150;
            } else {
                Log::debug('Using default template for post summary', $context);

                $systemPrompt = "You are an expert content summarizer. Create concise, engaging summaries that capture the key points of technical articles in 2-3 sentences.";
                $userPrompt = "Create a summary for this blog post titled '{$post->title}': {$content}";
                $parameters = $this->getDefaultParameters();
                $parameters['max_tokens'] = 150;
            }

            // Log prompt details if configured
            if (config('services.prism.log_prompts', false)) {
                Log::debug('Prompt details', $context + [
                    'system_prompt' => $systemPrompt,
                    'user_prompt'   => $userPrompt,
                    'parameters'    => $parameters
                ]);
            }

            // Make the API request
            $response = $this->prism->run([
                'model'    => $parameters['model'] ?? config('services.prism.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => $parameters['temperature'] ?? config('services.prism.default_temperature', 0.7),
                'max_tokens'  => $parameters['max_tokens'] ?? 150,
            ]);

            $summary = $response->content();

            // Log response if configured
            if (config('services.prism.log_responses', false)) {
                Log::debug('AI response', $context + [
                    'response' => $summary
                ]);
            }

            Log::info('AI summary generated successfully', $context);

            return $summary;
        } catch (\Exception $e) {
            Log::error('Error generating AI summary', [
                'post_id'       => $post->id,
                'error_message' => $e->getMessage(),
                'error_code'    => $e->getCode(),
                'error_file'    => $e->getFile(),
                'error_line'    => $e->getLine(),
            ]);

            return $post->description ?? substr(strip_tags($post->content), 0, 100) . '...';
        }
    }

    /**
     * Generate SEO metadata for a post.
     *
     * @param Post $post
     * @return array
     */
    public function generateSeoMetadata(Post $post): array
    {
        try {
            $context = [
                'post_id' => $post->id,
                'action'  => 'generate_seo_metadata'
            ];

            // Get template from database or use default
            $template = PromptTemplate::findByKey('seo_metadata');
            $content = substr(strip_tags($post->content), 0, 1000);

            if ($template) {
                Log::debug('Using database template for SEO metadata', $context + ['template_id' => $template->id]);

                // Format the template with variables
                $formatted = $template->format([
                    'title'       => $post->title,
                    'description' => $post->description,
                    'content'     => $content,
                ]);

                $systemPrompt = $formatted['system_prompt'];
                $userPrompt = $formatted['user_prompt'];
                $parameters = $formatted['parameters'] ?: $this->getDefaultParameters();
            } else {
                Log::debug('Using default template for SEO metadata', $context);

                $systemPrompt = "You are an SEO expert who helps writers optimize their content for search engines. Create metadata that accurately represents the content while being optimized for search.";
                $userPrompt = "Create SEO metadata for this blog post titled '{$post->title}'. The post is about: {$post->description}. Here's an excerpt: \"{$content}...\". Generate a JSON object with the following fields: meta_title, meta_description, and keywords (as an array).";
                $parameters = $this->getDefaultParameters();
            }

            $response = $this->prism->run([
                'model'    => $parameters['model'] ?? config('services.prism.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature'     => $parameters['temperature'] ?? config('services.prism.default_temperature', 0.7),
                'max_tokens'      => $parameters['max_tokens'] ?? 300,
                'response_format' => ['type' => 'json_object']
            ]);

            // Attempt to parse JSON response
            $metadata = json_decode($response->content(), true);

            // Validate and ensure required fields exist
            if (!is_array($metadata) || !isset($metadata['meta_title']) || !isset($metadata['meta_description'])) {
                throw new Exception('Invalid metadata format returned from AI');
            }

            Log::info('AI SEO metadata generated successfully', $context);

            return $metadata;
        } catch (\Exception $e) {
            Log::error('Error generating SEO metadata', [
                'post_id' => $post->id,
                'error'   => $e->getMessage()
            ]);

            // Fallback metadata
            return [
                'meta_title'       => $post->title,
                'meta_description' => $post->description ?? substr(strip_tags($post->content), 0, 160) . '...',
                'keywords'         => ['software engineering', 'laravel', 'web development'],
            ];
        }
    }

    /**
     * Get default parameters for API requests.
     *
     * @return array
     */
    private function getDefaultParameters(): array
    {
        return [
            'model'       => config('services.prism.model', 'ollama/mistral:latest'),
            'temperature' => config('services.prism.default_temperature', 0.7),
            'max_tokens'  => config('services.prism.default_max_tokens', 500),
        ];
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
            'twitter'  => "You are a tech-focused social media expert creating content for Twitter. Your posts are concise, engaging, and optimized for the platform. Include 2-3 relevant hashtags. Maximum length is 280 characters. Be impactful and conversational.",
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
}
