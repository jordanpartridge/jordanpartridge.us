<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('social_shares', function (Blueprint $table) {
            $table->id();
            $table->string('url')->index();
            $table->string('platform');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // Add indexes for performance
            $table->index(['platform', 'created_at']);
            $table->index(['post_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_shares');
    }
};
