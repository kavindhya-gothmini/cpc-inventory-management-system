<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fault_reports', function (Blueprint $table) {
            $table->foreignId('assigned_employee_id')
                ->nullable()
                ->after('assigned_to')
                ->constrained('employees')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fault_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_employee_id');
        });
    }
};
