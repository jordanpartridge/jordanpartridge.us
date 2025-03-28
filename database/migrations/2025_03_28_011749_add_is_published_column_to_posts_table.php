<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // We're checking if the column exists first to make this migration idempotent
        if (!Schema::hasColumn('posts', 'is_published')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->boolean('is_published')->default(false)->after('status');
            });

            // Update existing records based on the status field
            DB::statement("UPDATE posts SET is_published = 1 WHERE status = 'published'");
        } else {
            // If the column already exists, just make sure all published posts have is_published set to true
            DB::statement("UPDATE posts SET is_published = 1 WHERE status = 'published' AND is_published = 0");
        }

        // Add documentation to the migration about its purpose
        $this->command->info('Adding is_published column to posts table to properly handle published state.');
        $this->command->info('This migration ensures existing published posts have the correct is_published value.');
    }

    public function down(): void
    {
        // We don't want to drop the column in the rollback since other migrations might have added it
        // and we're making this migration idempotent
    }
};
