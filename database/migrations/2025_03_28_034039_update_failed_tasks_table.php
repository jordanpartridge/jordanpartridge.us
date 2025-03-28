<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('failed_tasks', function (Blueprint $table) {
            // Remove the old columns
            $table->dropForeign(['post_id']);
            $table->dropColumn(['post_id', 'platform', 'method']);

            // Add the new columns
            $table->string('task_type')->after('id');
            $table->string('entity_type')->after('task_type');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->json('context')->nullable()->after('error');
        });
    }

    public function down(): void
    {
        Schema::table('failed_tasks', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['task_type', 'entity_type', 'entity_id', 'context']);

            // Add the old columns back
            $table->foreignId('post_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('platform')->nullable()->after('post_id');
            $table->string('method')->after('platform');
        });
    }
};
