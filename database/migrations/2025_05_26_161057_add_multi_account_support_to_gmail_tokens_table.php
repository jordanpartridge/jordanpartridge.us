<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->dropIndex(['user_id', 'gmail_email']);
            $table->dropIndex(['user_id', 'is_primary']);

            $table->dropColumn([
                'gmail_email',
                'account_name',
                'is_primary',
                'last_sync_at',
                'account_info'
            ]);
        });
    }
};
