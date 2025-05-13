<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('client_email_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_email_id')->constrained()->cascadeOnDelete();
            $table->longText('html_content')->nullable();
            $table->longText('text_content')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index('client_email_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_email_bodies');
    }
};
