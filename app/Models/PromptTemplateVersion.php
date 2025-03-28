<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptTemplateVersion extends Model
{
    protected $fillable = [
        'prompt_template_id',
        'version',
        'system_prompt',
        'user_prompt',
        'parameters',
        'metrics',
        'created_by',
    ];

    protected $casts = [
        'parameters' => 'array',
        'metrics'    => 'array',
        'version'    => 'integer',
    ];

    /**
     * Get the template that owns this version.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(PromptTemplate::class, 'prompt_template_id');
    }
}
