<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('image')->nullable();
                $table->string('slug');
                $table->string('excerpt')->nullable();
                $table->string('type')->default('post');
                $table->string('status')->default('DRAFT');
                $table->boolean('active')->default(true);
                $table->boolean('featured')->default(false);
                $table->string('meta_title')->nullable();
                $table->string('meta_description')->nullable();
                $table->text('meta_schema')->nullable();
                $table->text('meta_data')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
