<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('gmail_tokens', function (Blueprint $table) {
            // Multi-account support
            $table->string('gmail_email')->nullable()->after('user_id');
            $table->string('account_name')->nullable()->after('gmail_email');
            $table->boolean('is_primary')->default(false)->after('account_name');
            $table->timestamp('last_sync_at')->nullable()->after('is_primary');
            $table->json('account_info')->nullable()->after('last_sync_at'); // Store additional Google profile info

            // Add composite index for efficient lookups
            $table->index(['user_id', 'gmail_email']);
            $table->index(['user_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::table('gmail_tokens', function (Blueprint $table) {
            // Drop indexes by their specific names (Laravel auto-generates these names)
            try {
                DB::statement('DROP INDEX gmail_tokens_user_id_is_primary_index ON gmail_tokens');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            try {
                DB::statement('DROP INDEX gmail_tokens_user_id_gmail_email_index ON gmail_tokens');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            // Drop columns added in up() method
            $table->dropColumn([
                'account_info',
                'last_sync_at',
                'is_primary',
                'account_name',
                'gmail_email'
            ]);
        });
    }
};
