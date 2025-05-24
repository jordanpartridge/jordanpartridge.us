<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('method', 10);
            $table->integer('response_time'); // in milliseconds
            $table->integer('response_status');
            $table->integer('memory_usage'); // in bytes
            $table->integer('peak_memory'); // in bytes
            $table->float('cpu_usage')->nullable();
            $table->integer('db_queries')->default(0);
            $table->integer('db_time')->default(0); // in milliseconds
            $table->integer('cache_hits')->default(0);
            $table->integer('cache_misses')->default(0);
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('additional_data')->nullable();
            $table->timestamps();
            $table->index(['url', 'created_at']);
            $table->index('response_time');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_metrics');
    }
};
