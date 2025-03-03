<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('github_repositories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('repository');
            $table->text('description')->nullable();
            $table->string('url');
            $table->json('technologies')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('stars_count')->default(0);
            $table->integer('forks_count')->default(0);
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_repositories');
    }
};
