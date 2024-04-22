<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->float('moving_time')->after('date')->nullable();
            $table->float('elapsed_time')->after('moving_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn('moving_time');
            $table->dropColumn('elapsed_time');
        });
    }
};
