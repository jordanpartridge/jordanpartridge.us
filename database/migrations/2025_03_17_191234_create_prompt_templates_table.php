<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique()->index();
            $table->text('system_prompt');
            $table->text('user_prompt');
            $table->string('content_type'); // e.g., social_post, summary, metadata
            $table->string('platform')->nullable(); // e.g., linkedin, twitter, null for generic
            $table->json('variables')->nullable(); // Available variables for this template
            $table->json('parameters')->nullable(); // Model parameters (temperature, max_tokens, etc.)
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->json('metrics')->nullable(); // Performance metrics
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Index for common queries
            $table->index(['content_type', 'platform', 'is_active']);
        });

        // Template versions table for history tracking
        Schema::create('prompt_template_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prompt_template_id');
            $table->unsignedInteger('version');
            $table->text('system_prompt');
            $table->text('user_prompt');
            $table->json('parameters')->nullable();
            $table->json('metrics')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('prompt_template_id')
                  ->references('id')
                  ->on('prompt_templates')
                  ->onDelete('cascade');

            $table->unique(['prompt_template_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_template_versions');
        Schema::dropIfExists('prompt_templates');
    }
};
