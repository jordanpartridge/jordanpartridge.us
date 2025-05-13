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
            $table->string('gmail_message_id')->nullable()->index();
            $table->string('subject')->nullable();
            $table->string('from')->nullable();
            $table->json('to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->text('snippet')->nullable();
            $table->longText('body')->nullable();
            $table->json('label_ids')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamp('email_date')->nullable();
            $table->timestamp('synchronized_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_emails');
    }
};
