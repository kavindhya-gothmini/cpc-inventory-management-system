<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_item_issues', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Issue Information
            |--------------------------------------------------------------------------
            */

            $table->dateTime('issue_datetime');

            // Manually entered temporary item code.
            // This is NOT linked to the Inventory items table.
            $table->string('item_code');

            // Examples: PC, Projector, Laptop
            $table->string('item_type');

            /*
            |--------------------------------------------------------------------------
            | Department / Functional Unit
            |--------------------------------------------------------------------------
            */

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
            | Event
            |--------------------------------------------------------------------------
            */

            $table->string('event');

            /*
            |--------------------------------------------------------------------------
            | Requesting Employee
            |--------------------------------------------------------------------------
            */

            $table->foreignId('requesting_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Transporting Employee
            |--------------------------------------------------------------------------
            */

            $table->foreignId('transporting_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Receiving Information
            |--------------------------------------------------------------------------
            */

            $table->dateTime('received_datetime')
                ->nullable();

            $table->foreignId('receiving_employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'transported',
                'received',
                'cancelled',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | System Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('item_code');
            $table->index('item_type');
            $table->index('status');
            $table->index('issue_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_item_issues');
    }
};

