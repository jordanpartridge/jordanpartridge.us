<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('strava_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->dateTime('expires_at');
            $table->text('refresh_token');
            $table->unsignedBigInteger('athlete_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strava_tokens');
    }
};
