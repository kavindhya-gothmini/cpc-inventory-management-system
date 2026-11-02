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
        Schema::table('users', function (Blueprint $table) {
            $table->string('epf_number')->nullable()->unique()->after('name');
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->string('role')->default('employee')->after('phone');
            $table->foreignId('employee_id')
                ->nullable()
                ->after('role')
                ->constrained('employees')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn([
                'epf_number',
                'phone',
                'role',
                'employee_id',
            ]);
        });
    }
};