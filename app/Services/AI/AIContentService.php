<?php

namespace App\Services\AI;

use App\Models\FailedTask;
use App\Models\Post;
use App\Models\PromptTemplate;
use Exception;
use Illuminate\Support\Facades\Log;
use Prism\Prism;

class AIContentService
{
    private $prism;

    public function __construct(Prism $prism)
    {
        $this->prism = $prism;
    }

    /**
     * Generates a polished social media post for the given platform.
     *
     * @throws Exception if AI generation fails
     */
    public function generateSocialPost(Post $post, string $platform = 'linkedin'): string
    {
        try {
            $template = $this->fetchTemplate("social_post_{$platform}", 'social_post', $platform);

            $systemPrompt = $template->system_prompt ?? 'You are a professional content creator specializing in tech and software content.';
            $userPrompt = $this->populateTemplate(
                $template->user_prompt ?? 'Create a social post about {title}. Topic: {description}',
                [
                    'title'       => $post->title,
                    'description' => $post->description ?? $post->body ?? 'technology and software',
                    'excerpt'     => $post->excerpt ?? $this->truncate($post->body ?? $post->content ?? '', 150),
                    'platform'    => $platform,
                ]
            );

            $params = [
                'model' => config('services.prism.model', 'ollama/mistral:latest'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => $template->parameters['temperature'] ?? 0.7,
                'max_tokens' => $template->parameters['max_tokens'] ?? 500,
            ];

            $response = $this->prism->run($params);
            return $response->content();
        } catch (Exception $e) {
            $this->recordError('generateSocialPost', $post->id, $platform, $e->getMessage());
            
            // Check for fallback model configuration
            $fallbackModel = config('services.prism.fallback_model');
            if ($fallbackModel) {
                try {
                    $params['model'] = $fallbackModel;
                    $response = $this->prism->run($params);
                    return $response->content();
                } catch (Exception $fallbackException) {
                    $this->recordError('generateSocialPost-fallback', $post->id, $platform, $fallbackException->getMessage());
                }
            }
            
            // Return fallback content
            return "I'm excited to share my latest article: \"{$post->title}\"\n\n{$post->description}\n\nCheck it out on my website!";
        }
    }

    /**
     * Crafts a concise summary of the post content.
     *
     * @throws Exception if AI generation fails
     */
    public function generateSummary(Post $post): string
    {
        try {
            $template = $this->fetchTemplate('summary', 'summary', null);

            $systemPrompt = $template->system_prompt ?? 'You are an expert content summarizer.';
            $userPrompt = $this->populateTemplate(
                $template->user_prompt ?? 'Summarize the following content in 2-3 sentences: {body}',
                [
                    'title' => $post->title,
                    'body'  => $post->body ?? $post->content ?? '',
                ]
            );

            $params = [
                'model' => config('services.prism.model', 'ollama/mistral:latest'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => $template->parameters['temperature'] ?? 0.7,
                'max_tokens' => $template->parameters['max_tokens'] ?? 150,
            ];

            $response = $this->prism->run($params);
            return $response->content();
        } catch (Exception $e) {
            $this->recordError('generateSummary', $post->id, null, $e->getMessage());
            throw $e;
        }
    }

    /**
     * Produces optimized SEO metadata for the post.
     *
     * @throws Exception if AI generation fails
     * @return array{meta_title: string, meta_description: string, keywords: string[]}
     */
    public function generateSeoMetadata(Post $post): array
    {
        try {
            $template = $this->fetchTemplate('seo_metadata', 'seo_metadata', null);

            $systemPrompt = $template->system_prompt ?? 'You are an SEO expert crafting optimized metadata for blog posts.';
            $userPrompt = $this->populateTemplate(
                $template->user_prompt ?? 'Create SEO metadata for a blog post titled "{title}". Description: {description}. Content: {content}',
                [
                    'title'       => $post->title,
                    'description' => $post->description ?? '',
                    'content'     => $this->truncate($post->body ?? $post->content ?? '', 1000),
                ]
            );

            $params = [
                'model' => config('services.prism.model', 'ollama/mistral:latest'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => $template->parameters['temperature'] ?? 0.7,
                'max_tokens' => $template->parameters['max_tokens'] ?? 500,
                'response_format' => ['type' => 'json_object'],
            ];

            $response = $this->prism->run($params);
            $metadata = json_decode($response->content(), true, 512, JSON_THROW_ON_ERROR);

            return [
                'meta_title'       => $metadata['meta_title'] ?? $post->title,
                'meta_description' => $metadata['meta_description'] ?? $post->description,
                'keywords'         => $metadata['keywords'] ?? ['laravel', 'php', 'web development'],
            ];
        } catch (Exception $e) {
            $this->recordError('generateSeoMetadata', $post->id, null, $e->getMessage());
            
            // Return basic metadata as fallback
            return [
                'meta_title'       => $post->title . ' | Jordan Partridge',
                'meta_description' => $this->truncate($post->description ?? $post->content ?? '', 150),
                'keywords'         => ['laravel', 'php', 'web development'],
            ];
        }
    }

    /**
     * Records an error for later analysis and retry.
     */
    private function recordError(string $method, ?int $postId, ?string $platform, string $error): void
    {
        Log::error("AI Content generation failed: {$method} - Post ID: {$postId}, Platform: {$platform}, Error: {$error}");

        FailedTask::create([
            'post_id'  => $postId,
            'platform' => $platform,
            'method'   => $method,
            'error'    => $error,
            'attempts' => 1,
        ]);
    }

    /**
     * Fetches a prompt template or provides a sensible default.
     */
    private function fetchTemplate(string $key, string $type, ?string $platform = null): object
    {
        return PromptTemplate::where('key', $key)
            ->when($platform, fn ($query) => $query->where('platform', $platform))
            ->where('is_active', true)
            ->first() 
            ?? (object) [
                'system_prompt' => "You are a professional content creator specializing in writing for " . ($platform ?? $type) . ".",
                'user_prompt'   => 'Create content about {title}. Use this description: {description}',
                'parameters'    => [
                    'temperature' => config('services.prism.default_temperature', 0.7),
                    'max_tokens'  => config('services.prism.default_max_tokens', 500),
                ],
            ];
    }

    /**
     * Populates a template string with variable values.
     */
    private function populateTemplate(string $template, array $variables): string
    {
        return str_replace(
            array_map(fn ($key) => "{{$key}}", array_keys($variables)),
            array_values($variables),
            $template
        );
    }

    /**
     * Truncates text cleanly with an ellipsis.
     */
    private function truncate(?string $text, int $length): string
    {
        if (empty($text)) {
            return '';
        }
        
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }
}