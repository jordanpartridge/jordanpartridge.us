<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('failed_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform')->nullable();
            $table->string('method');
            $table->text('error');
            $table->unsignedInteger('attempts')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_tasks');
    }
};
