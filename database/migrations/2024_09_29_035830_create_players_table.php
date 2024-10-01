<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->snowflakeId();
            $table->foreignId('game_id')->nullable()->constrained();
            $table->string('name');
            $table->integer('balance')->default(0);
            $table->json('hand')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
