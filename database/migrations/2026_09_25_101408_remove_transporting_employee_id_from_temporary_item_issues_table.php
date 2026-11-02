<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_item_issues', function (Blueprint $table) {

            if (Schema::hasColumn(
                'temporary_item_issues',
                'transporting_employee_id'
            )) {
                $table->dropForeign([
                    'transporting_employee_id',
                ]);

                $table->dropColumn(
                    'transporting_employee_id'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('temporary_item_issues', function (Blueprint $table) {

            if (!Schema::hasColumn(
                'temporary_item_issues',
                'transporting_employee_id'
            )) {
                $table->foreignId('transporting_employee_id')
                    ->nullable()
                    ->after('requesting_employee_id')
                    ->constrained('employees')
                    ->nullOnDelete();
            }
        });
    }
};