<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {
            $table->timestamp('changed_at')
                ->nullable();

            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {
            $table->dropIndex([
                'temporary_item_issue_histories_changed_at_index',
            ]);

            $table->dropColumn('changed_at');
        });
    }
};

