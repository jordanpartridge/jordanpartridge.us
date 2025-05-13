<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('client_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('gmail_message_id')->unique();
            $table->string('subject');
            $table->string('snippet', 1000)->nullable();
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('received_at');
            $table->string('thread_id')->index();
            $table->json('labels')->nullable();
            $table->boolean('has_attachment')->default(false);
            $table->timestamps();

            // Indexes for faster lookups
            $table->index(['client_id', 'received_at']);
            $table->index(['is_sent', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_emails');
    }
};
