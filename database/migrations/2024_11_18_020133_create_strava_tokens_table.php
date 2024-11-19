<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the strava_tokens table.
 *
 * This table stores Strava OAuth authentication tokens and related data
 * for each user in the application.
 */
return new class () extends Migration {
    public function up()
    {
        Schema::create('strava_tokens', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // OAuth tokens - stored as text due to encryption
            $table->text('access_token')
                ->comment('Encrypted Strava API access token');
            $table->text('refresh_token')
                ->comment('Encrypted Strava API refresh token');

            // Token expiration timestamp
            $table->timestamp('expires_at')
                ->comment('Timestamp when the access token expires');

            // Strava athlete identification
            $table->string('athlete_id')
                ->nullable()
                ->comment('Strava athlete ID associated with the tokens');

            // Standard timestamps (created_at, updated_at)
            $table->timestamps();

            // Optional: Add index for faster queries
            $table->index('athlete_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('strava_tokens');
    }
};
