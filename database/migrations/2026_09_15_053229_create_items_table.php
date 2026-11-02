<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Item Classification
            |--------------------------------------------------------------------------
            */
            $table->foreignId('item_type_id')
                ->constrained('item_types')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Asset Identification
            |--------------------------------------------------------------------------
            */
            $table->string('asset_no')
                ->unique();

            $table->string('make')
                ->nullable();

            $table->string('model');

            $table->string('serial_no')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Network Information
            |--------------------------------------------------------------------------
            */
            $table->string('ip_address')
                ->nullable();

            $table->string('workstation_id')
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('function_unit_id')
                ->nullable()
                ->constrained('function_units')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Current Assignment
            |--------------------------------------------------------------------------
            */
            $table->foreignId('current_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Item Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'available',
                'in_use',
                'under_repair',
                'disabled',
            ])->default('available');

            /*
            |--------------------------------------------------------------------------
            | General Information
            |--------------------------------------------------------------------------
            */
            $table->text('description')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};