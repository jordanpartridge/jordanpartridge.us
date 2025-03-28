<?php

namespace App\Services\AI;

use App\Models\Post;
use App\Models\PromptTemplate;
use App\Services\AI\Exceptions\AIGenerationException;
use App\Services\AI\Handlers\ExceptionHandler;
use App\Services\AI\Handlers\FallbackHandler;
use Exception;
use Prism\Prism;

class AIContentService
{
    /**
     * @var Prism
     */
    protected $prism;

    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandler;

    /**
     * @var FallbackHandler
     */
    protected $fallbackHandler;

    /**
     * Create a new AI content service instance.
     *
     * @param Prism $prism
     * @param ExceptionHandler $exceptionHandler
     * @param FallbackHandler $fallbackHandler
     */
    public function __construct(
        Prism $prism,
        ExceptionHandler $exceptionHandler = null,
        FallbackHandler $fallbackHandler = null
    ) {
        $this->prism = $prism;
        $this->exceptionHandler = $exceptionHandler ?? new ExceptionHandler();
        $this->fallbackHandler = $fallbackHandler ?? new FallbackHandler($prism, $this->exceptionHandler);
    }

    /**
     * Generate a social media post for the given post and platform.
     *
     * @param Post $post
     * @param string $platform
     * @return string
     */
    public function generateSocialPost(Post $post, string $platform = 'linkedin'): string
    {
        $operation = 'generateSocialPost';

        try {
            // Fetch template and prepare parameters
            $template = $this->fetchTemplate("social_post_{$platform}", 'social_post', $platform);
            $params = $this->prepareParameters($template, [
                'title'       => $post->title,
                'description' => $post->description ?? $post->body ?? 'technology and software',
                'excerpt'     => $post->excerpt ?? $this->truncate($post->body ?? $post->content ?? '', 150),
                'platform'    => $platform,
            ]);

            // Execute the AI request
            $response = $this->prism->run($params);
            return $response->content();
        } catch (Exception $e) {
            // Create an AIGenerationException
            $exception = new AIGenerationException(
                $operation,
                $post->id,
                $platform,
                $e->getMessage(),
                false,
                $e
            );

            // Handle the exception
            $this->exceptionHandler->handleException($exception);

            // Try fallback model if available
            if (isset($params)) {
                $fallbackContent = $this->fallbackHandler->attemptFallbackModel(
                    $operation,
                    $post->id,
                    $platform,
                    $params,
                    $e
                );

                if ($fallbackContent !== null) {
                    return $fallbackContent;
                }
            }

            // Return default fallback content
            return $this->exceptionHandler->getFallbackContent($operation, $post, $platform);
        }
    }

    /**
     * Generate a summary for the given post.
     *
     * @param Post $post
     * @return string
     * @throws AIGenerationException if generation fails
     */
    public function generateSummary(Post $post): string
    {
        $operation = 'generateSummary';

        try {
            // Fetch template and prepare parameters
            $template = $this->fetchTemplate('summary', 'summary', null);
            $params = $this->prepareParameters($template, [
                'title' => $post->title,
                'body'  => $post->body ?? $post->content ?? '',
            ]);

            // Execute the AI request
            $response = $this->prism->run($params);
            return $response->content();
        } catch (Exception $e) {
            // Create an AIGenerationException
            $exception = new AIGenerationException(
                $operation,
                $post->id,
                null,
                $e->getMessage(),
                false,
                $e
            );

            // Handle the exception
            $this->exceptionHandler->handleException($exception);

            // Rethrow the exception since this method doesn't handle fallbacks internally
            throw $exception;
        }
    }

    /**
     * Generate SEO metadata for the given post.
     *
     * @param Post $post
     * @return array{meta_title: string, meta_description: string, keywords: string[]}
     */
    public function generateSeoMetadata(Post $post): array
    {
        $operation = 'generateSeoMetadata';

        try {
            // Fetch template and prepare parameters
            $template = $this->fetchTemplate('seo_metadata', 'seo', null);
            $params = $this->prepareParameters($template, [
                'title' => $post->title,
                'body'  => $post->body ?? $post->content ?? '',
            ]);

            // Add response format parameter if not already set
            if (!isset($params['response_format'])) {
                $params['response_format'] = [
                    'type'   => 'json_object',
                    'schema' => [
                        'type'       => 'object',
                        'properties' => [
                            'meta_title' => [
                                'type'        => 'string',
                                'description' => 'SEO optimized title, max 60 chars'
                            ],
                            'meta_description' => [
                                'type'        => 'string',
                                'description' => 'SEO optimized description, max 160 chars'
                            ],
                            'keywords' => [
                                'type'  => 'array',
                                'items' => [
                                    'type' => 'string'
                                ],
                                'description' => 'List of relevant keywords for the post'
                            ]
                        ],
                        'required' => ['meta_title', 'meta_description', 'keywords']
                    ]
                ];
            }

            // Execute the AI request
            $response = $this->prism->run($params);
            $content = $response->content();

            // Parse the JSON response
            $data = json_decode($content, true);

            // Validate the required fields
            if (
                !is_array($data) ||
                !isset($data['meta_title']) ||
                !isset($data['meta_description']) ||
                !isset($data['keywords']) ||
                !is_array($data['keywords'])
            ) {
                throw new Exception("Invalid response format: " . substr($content, 0, 100));
            }

            // Ensure the title and description meet requirements
            $data['meta_title'] = substr($data['meta_title'], 0, 60);
            $data['meta_description'] = substr($data['meta_description'], 0, 160);

            return $data;
        } catch (Exception $e) {
            // Create an AIGenerationException
            $exception = new AIGenerationException(
                $operation,
                $post->id,
                null,
                $e->getMessage(),
                false,
                $e
            );

            // Handle the exception
            $this->exceptionHandler->handleException($exception);

            // Try fallback model if available
            if (isset($params)) {
                $fallbackContent = $this->fallbackHandler->attemptFallbackModel(
                    $operation,
                    $post->id,
                    null,
                    $params,
                    $e
                );

                if ($fallbackContent !== null) {
                    // Parse JSON from fallback model
                    $fallbackData = json_decode($fallbackContent, true);

                    if (
                        is_array($fallbackData) &&
                        isset($fallbackData['meta_title']) &&
                        isset($fallbackData['meta_description']) &&
                        isset($fallbackData['keywords']) &&
                        is_array($fallbackData['keywords'])
                    ) {
                        // Ensure the title and description meet requirements
                        $fallbackData['meta_title'] = substr($fallbackData['meta_title'], 0, 60);
                        $fallbackData['meta_description'] = substr($fallbackData['meta_description'], 0, 160);

                        return $fallbackData;
                    }
                }
            }

            // Return default fallback content
            return $this->exceptionHandler->getFallbackContent($operation, $post);
        }
    }

    /**
     * Prepare the parameters for an AI request.
     *
     * @param PromptTemplate $template
     * @param array $variables
     * @return array
     */
    protected function prepareParameters(PromptTemplate $template, array $variables): array
    {
        $systemPrompt = $template->system_prompt ?? 'You are a professional content creator.';
        $userPrompt = $this->populateTemplate(
            $template->user_prompt ?? 'Create content about {title}.',
            $variables
        );

        return [
            'model'    => config('services.prism.model', 'ollama/mistral:latest'),
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => $template->parameters['temperature'] ?? 0.7,
            'max_tokens'  => $template->parameters['max_tokens'] ?? 500,
        ];
    }

    /**
     * Fetch a template from the database or defaults.
     *
     * @param string $key
     * @param string $type
     * @param string|null $platform
     * @return PromptTemplate
     */
    protected function fetchTemplate(string $key, string $type, ?string $platform): PromptTemplate
    {
        // Try to get the template from the database
        $template = PromptTemplate::findByKey($key);

        // If not found, create a default template
        if (!$template) {
            $template = new PromptTemplate();
            $template->name = ucfirst(str_replace('_', ' ', $key));
            $template->key = $key;
            $template->type = $type;

            // Default templates based on type
            switch ($type) {
                case 'social_post':
                    $template->system_prompt = "You are a professional content creator specializing in tech and software content for {$platform}.";
                    $template->user_prompt = "Create a {$platform} post about {title}. Topic: {description}";
                    $template->parameters = [
                        'temperature' => 0.7,
                        'max_tokens'  => 500,
                    ];
                    break;

                case 'summary':
                    $template->system_prompt = "You are an expert content summarizer.";
                    $template->user_prompt = "Summarize the following content in 2-3 sentences: {body}";
                    $template->parameters = [
                        'temperature' => 0.5,
                        'max_tokens'  => 150,
                    ];
                    break;

                case 'seo':
                    $template->system_prompt = "You are an SEO expert.";
                    $template->user_prompt = "Create SEO metadata for the following content. Title: {title}. Content: {body}";
                    $template->parameters = [
                        'temperature' => 0.3,
                        'max_tokens'  => 300,
                    ];
                    break;

                default:
                    $template->system_prompt = "You are a professional content creator.";
                    $template->user_prompt = "Create content about {title}.";
                    $template->parameters = [
                        'temperature' => 0.7,
                        'max_tokens'  => 500,
                    ];
            }
        }

        return $template;
    }

    /**
     * Populate a template with variables.
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    protected function populateTemplate(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }

    /**
     * Truncate text to the given length, ensuring it ends at a word boundary.
     *
     * @param string $text
     * @param int $length
     * @param string $append
     * @return string
     */
    protected function truncate(string $text, int $length = 100, string $append = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $truncated = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return $truncated . $append;
    }
}
