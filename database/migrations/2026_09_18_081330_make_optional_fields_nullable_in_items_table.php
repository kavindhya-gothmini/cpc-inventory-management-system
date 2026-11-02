<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('make')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('serial_number')->nullable()->change();

            $table->string('ip_address')->nullable()->change();
            $table->string('workstation_id')->nullable()->change();

            $table->unsignedBigInteger('branch_id')->nullable()->change();
            $table->unsignedBigInteger('department_id')->nullable()->change();
            $table->unsignedBigInteger('function_unit_id')->nullable()->change();

            $table->text('description')->nullable()->change();
            $table->text('notes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('make')->nullable(false)->change();
            $table->string('model')->nullable(false)->change();
            $table->string('serial_number')->nullable(false)->change();

            $table->string('ip_address')->nullable(false)->change();
            $table->string('workstation_id')->nullable(false)->change();

            $table->unsignedBigInteger('branch_id')->nullable(false)->change();
            $table->unsignedBigInteger('department_id')->nullable(false)->change();
            $table->unsignedBigInteger('function_unit_id')->nullable(false)->change();

            $table->text('description')->nullable(false)->change();
            $table->text('notes')->nullable(false)->change();
        });
    }
};