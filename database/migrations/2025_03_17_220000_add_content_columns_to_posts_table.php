<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'description')) {
                $table->text('description')->nullable()->after('meta_data');
            }
            if (!Schema::hasColumn('posts', 'content')) {
                $table->longText('content')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['description', 'content']);
        });
    }
};
