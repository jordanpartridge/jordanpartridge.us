# AI Content Generation Service

## Overview
This service provides AI-powered content generation for various purposes including:
- Social media posts for different platforms (LinkedIn, Twitter)
- Blog post summaries
- SEO metadata generation
- Related content suggestions

## Installation

The service uses the Prism PHP package for AI model interactions:

```bash
composer require prism-php/prism
```

## Configuration
The service is configured in `config/services.php` under the `prism` key with expanded configuration options:

```php
'prism' => [
    // API Connection Settings
    'api_key' => env('PRISM_API_KEY'),
    'base_url' => env('PRISM_BASE_URL', 'https://api.prism.ai'),
    'timeout' => env('PRISM_REQUEST_TIMEOUT', 30),
    
    // Default Model Settings
    'model' => env('PRISM_DEFAULT_MODEL', 'ollama/mistral:latest'),
    'fallback_model' => env('PRISM_FALLBACK_MODEL', 'ollama/mistral:7b-instruct-q4_0'),
    
    // Content Generation Parameters
    'default_temperature' => env('PRISM_DEFAULT_TEMPERATURE', 0.7),
    'default_max_tokens' => env('PRISM_DEFAULT_MAX_TOKENS', 500),
    
    // Prompt Storage
    'template_storage' => env('PRISM_TEMPLATE_STORAGE', 'database'), // Options: database, json, yaml
    'template_path' => env('PRISM_TEMPLATE_PATH', database_path('ai/templates')),
    
    // Logging & Debug
    'log_level' => env('PRISM_LOG_LEVEL', 'info'), // Options: debug, info, warning, error
    'log_prompts' => env('PRISM_LOG_PROMPTS', false),
    'log_responses' => env('PRISM_LOG_RESPONSES', false),
],
```

### Required Environment Variables
- `PRISM_API_KEY`: Your API key for the Prism service
- `PRISM_DEFAULT_MODEL`: The default AI model to use (default: ollama/mistral:latest)

### Optional Environment Variables
- `PRISM_BASE_URL`: The base URL for API requests (default: https://api.prism.ai)
- `PRISM_REQUEST_TIMEOUT`: Timeout in seconds for API requests (default: 30)
- `PRISM_FALLBACK_MODEL`: Fallback model to use if the primary model fails
- `PRISM_DEFAULT_TEMPERATURE`: Sampling temperature for generation (default: 0.7)
- `PRISM_DEFAULT_MAX_TOKENS`: Maximum tokens to generate (default: 500)
- `PRISM_TEMPLATE_STORAGE`: Storage mechanism for prompt templates (default: database)
- `PRISM_TEMPLATE_PATH`: Path for file-based template storage
- `PRISM_LOG_LEVEL`: Logging detail level (default: info)
- `PRISM_LOG_PROMPTS`: Whether to log prompt details (default: false)
- `PRISM_LOG_RESPONSES`: Whether to log AI responses (default: false)

## Using AI Features in the Admin Panel

### Creating a New Post

1. Navigate to **Blog Management > Posts > Create Post**
2. Fill in the post title and content in the **Details** and **Content** tabs
3. Use the AI features in the following tabs:

#### SEO Tab

1. After adding your post title and content, click on the **SEO** tab
2. Click the **Generate with AI** button next to "SEO Metadata"
3. The system will generate an optimized meta title, description, and keywords
4. Review and edit the generated content if needed

#### Social Media Tab

1. Enable the **Automatically generate social media posts** toggle
2. Select which platforms you want to generate content for (LinkedIn, Twitter/X)
3. Preview the AI-generated social media posts that will be published when your post goes live
4. To regenerate any content, use the "Regenerate" button in the preview

### Publishing a Post with Social Sharing

1. Set your post status to **Published**
2. If you've enabled auto-generate social media posts, the system will automatically:
   - Generate optimized social media content for your selected platforms
   - Share the content to those platforms upon post publication
3. You'll receive a notification confirming successful social media sharing

### Social Preview Modal

You can preview how your social media posts will look before publishing:

1. While editing a post, go to the **Content** tab
2. Click the **Preview Social Media Posts** button
3. A modal will display AI-generated previews for LinkedIn and Twitter/X
4. Click **Regenerate** to get new AI-generated versions
5. Click **Close** when done

## Usage Examples

### Generating Social Media Posts

```php
use App\Services\AI\AIContentService;

// Inject the service
public function __construct(protected AIContentService $aiService)
{
    //
}

// Generate social media content
$linkedInPost = $this->aiService->generateSocialPost($post, 'linkedin');
$twitterPost = $this->aiService->generateSocialPost($post, 'twitter');
```

### Generating Post Summaries

```php
$summary = $this->aiService->generateSummary($post);
```

### Generating SEO Metadata

```php
$metadata = $this->aiService->generateSeoMetadata($post);

// Use the metadata in your application
$metaTitle = $metadata['meta_title'];
$metaDescription = $metadata['meta_description'];
$keywords = $metadata['keywords']; // Array of keywords
```

## Prompt Templates System

### Database Templates
Prompt templates are stored in the database and can be managed through the admin interface or directly via the `PromptTemplate` model.

```php
use App\Models\PromptTemplate;

// Create a new template
$template = PromptTemplate::create([
    'name' => 'LinkedIn Post Template',
    'key' => 'social_post',
    'platform' => 'linkedin',
    'content_type' => 'social_post',
    'system_prompt' => 'You are a professional content creator...',
    'user_prompt' => 'Create a LinkedIn post about {title}...',
    'variables' => ['title', 'description', 'excerpt', 'platform'],
    'parameters' => [
        'temperature' => 0.7,
        'max_tokens' => 300
    ],
    'is_active' => true,
    'description' => 'Template for generating LinkedIn posts',
]);

// Find a template by key
$template = PromptTemplate::findByKey('social_post', 'linkedin');

// Format a template with variables
$formatted = $template->format([
    'title' => 'My Blog Post',
    'description' => 'A description...',
    'excerpt' => 'An excerpt...',
    'platform' => 'linkedin'
]);
```

## Error Handling and Fallbacks

The service includes comprehensive error handling:

1. **Primary Model Failure**: If the primary model fails, the service attempts to use the fallback model if configured.
2. **Fallback Template**: If all AI generation attempts fail, the service falls back to template-based content.
3. **Detailed Logging**: All errors are logged with context including error messages, file, line, and stack traces.

## Testing

Run the test suite to verify the AI service is functioning correctly:

```bash
php artisan test --filter=AIContentServiceTest
```

## Troubleshooting

Common issues and solutions:

1. **API Connection Errors**: Check your API key and network connectivity
2. **Timeout Errors**: Increase the `PRISM_REQUEST_TIMEOUT` value
3. **Model Unavailability**: Configure a fallback model
4. **Generation Quality Issues**: Adjust temperature or modify prompt templates
5. **Rate Limiting**: Implement queue processing and caching