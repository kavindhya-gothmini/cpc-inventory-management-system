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
        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->foreignId('temporary_item_id')
                ->nullable()
                ->after('issue_datetime')
                ->constrained('temporary_items')
                ->nullOnDelete();

            $table->index('temporary_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->dropForeign(['temporary_item_id']);
            $table->dropIndex(['temporary_item_id']);
            $table->dropColumn('temporary_item_id');
        });
    }
};