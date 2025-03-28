# AI Template Storage

This directory contains prompt templates for AI content generation in various formats. The templates can be stored in JSON, YAML, or other serialization formats.

## Structure

Templates are organized by content type and platform:

```
/templates
  /social_post
    /linkedin
      - default.json
      - professional.json
      - casual.json
    /twitter
      - default.json
      - technical.json
  /summary
    - default.json
    - technical.json
  /seo_metadata
    - default.json
```

## Format

Each template file follows a standardized schema:

### JSON Format

```json
{
  "name": "LinkedIn Post Template",
  "key": "social_post",
  "system_prompt": "You are a professional content creator specializing in tech content...",
  "user_prompt": "Create a LinkedIn post about {title}. The post is about: {description}...",
  "content_type": "social_post",
  "platform": "linkedin",
  "variables": ["title", "description", "excerpt", "platform"],
  "parameters": {
    "temperature": 0.7,
    "max_tokens": 300
  },
  "version": 1,
  "description": "Standard template for generating LinkedIn posts"
}
```

### YAML Format

```yaml
name: LinkedIn Post Template
key: social_post
system_prompt: You are a professional content creator specializing in tech content...
user_prompt: Create a LinkedIn post about {title}. The post is about {description}...
content_type: social_post
platform: linkedin
variables:
  - title
  - description
  - excerpt
  - platform
parameters:
  temperature: 0.7
  max_tokens: 300
version: 1
description: Standard template for generating LinkedIn posts
```

## Usage

These templates can be loaded via the AIContentService based on the configured template storage mechanism in `config/services.php`:

```php
'template_storage' => env('PRISM_TEMPLATE_STORAGE', 'database'), // Options: database, json, yaml
'template_path' => env('PRISM_TEMPLATE_PATH', database_path('ai/templates')),
```

When set to 'json' or 'yaml', the service will load templates from this directory instead of the database.