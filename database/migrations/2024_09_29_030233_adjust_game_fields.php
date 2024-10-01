<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('deck_slug');
            $table->snowflake('game_id')->after('id');
        });
    }

    public function down(): void
    {
        //
    }
};
