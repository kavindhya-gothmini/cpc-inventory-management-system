<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->foreignId('collecting_employee_id')
                ->nullable()
                ->after('requesting_employee_id')
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('returning_employee_id')
                ->nullable()
                ->after('received_datetime')
                ->constrained('employees')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->dropForeign(['collecting_employee_id']);
            $table->dropColumn('collecting_employee_id');

            $table->dropForeign(['returning_employee_id']);
            $table->dropColumn('returning_employee_id');
        });
    }
};

