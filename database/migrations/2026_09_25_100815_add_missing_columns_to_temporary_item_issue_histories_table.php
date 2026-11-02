<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'action'
            )) {
                $table->string('action')
                    ->after('temporary_item_issue_id');
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'description'
            )) {
                $table->text('description')
                    ->nullable()
                    ->after('action');
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_by'
            )) {
                $table->foreignId('changed_by')
                    ->nullable()
                    ->after('description')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_by_name'
            )) {
                $table->string('changed_by_name')
                    ->nullable()
                    ->after('changed_by');
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_by_epf'
            )) {
                $table->string('changed_by_epf')
                    ->nullable()
                    ->after('changed_by_name');
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_by_role'
            )) {
                $table->string('changed_by_role')
                    ->nullable()
                    ->after('changed_by_epf');
            }

            if (!Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_at'
            )) {
                $table->timestamp('changed_at')
                    ->nullable()
                    ->after('changed_by_role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('temporary_item_issue_histories', function (Blueprint $table) {

            if (Schema::hasColumn(
                'temporary_item_issue_histories',
                'changed_by'
            )) {
                $table->dropForeign([
                    'changed_by',
                ]);
            }

            $columns = [
                'action',
                'description',
                'changed_by',
                'changed_by_name',
                'changed_by_epf',
                'changed_by_role',
                'changed_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn(
                    'temporary_item_issue_histories',
                    $column
                )) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};