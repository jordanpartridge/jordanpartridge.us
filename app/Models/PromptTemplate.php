<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromptTemplate extends Model
{
    protected $fillable = [
        'name',
        'key',
        'system_prompt',
        'user_prompt',
        'content_type',
        'platform',
        'variables',
        'parameters',
        'version',
        'is_active',
        'description',
        'metrics',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'variables'  => 'array',
        'parameters' => 'array',
        'metrics'    => 'array',
        'is_active'  => 'boolean',
        'version'    => 'integer',
    ];

    /**
     * Find a template by its key.
     *
     * @param string $key
     * @param string|null $platform
     * @return self|null
     */
    public static function findByKey(string $key, ?string $platform = null): ?self
    {
        $query = self::where('key', $key)
                     ->where('is_active', true);

        if ($platform) {
            $query->where('platform', $platform);
        }

        return $query->first();
    }

    /**
     * Get the versions for this template.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(PromptTemplateVersion::class);
    }

    /**
     * Format the template with the given variables.
     *
     * @param array $variables
     * @return array
     */
    public function format(array $variables = []): array
    {
        $systemPrompt = $this->system_prompt;
        $userPrompt = $this->user_prompt;

        // Replace variables in the prompts
        foreach ($variables as $key => $value) {
            $systemPrompt = str_replace("{{$key}}", $value, $systemPrompt);
            $userPrompt = str_replace("{{$key}}", $value, $userPrompt);
        }

        return [
            'system_prompt' => $systemPrompt,
            'user_prompt'   => $userPrompt,
            'parameters'    => $this->parameters ?? [],
        ];
    }

    /**
     * Create a new version of this template.
     *
     * @param int|null $createdBy
     * @return void
     */
    public function createVersion(?int $createdBy = null): void
    {
        $this->versions()->create([
            'version'       => $this->version,
            'system_prompt' => $this->system_prompt,
            'user_prompt'   => $this->user_prompt,
            'parameters'    => $this->parameters,
            'metrics'       => $this->metrics,
            'created_by'    => $createdBy ?? $this->created_by,
        ]);

        // Increment the version number
        $this->increment('version');
    }
}
