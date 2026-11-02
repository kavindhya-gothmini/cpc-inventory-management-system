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
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {
            $table->foreignId('temporary_item_issue_id')
                ->after('id')
                ->constrained('temporary_item_issues')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {
            $table->dropForeign([
                'temporary_item_issue_id',
            ]);

            $table->dropColumn('temporary_item_issue_id');
        });
    }
};

