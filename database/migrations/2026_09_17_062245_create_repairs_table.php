<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Inventory Item
            |--------------------------------------------------------------------------
            */
            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Repair Information
            |--------------------------------------------------------------------------
            */
            $table->string('repair_reference', 100)
                ->unique();

            $table->date('date_sent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Responsible Employee
            |--------------------------------------------------------------------------
            */
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Carrier / Person Who Delivered or Collected Item
            |--------------------------------------------------------------------------
            */
            $table->string('carrier', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Problem / Repair Details
            |--------------------------------------------------------------------------
            */
            $table->text('problem_description')
                ->nullable();

            $table->string('technician', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Repair Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'pending',
                'sent_for_repair',
                'under_repair',
                'completed',
                'returned',
                'cancelled',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Return Information
            |--------------------------------------------------------------------------
            */
            $table->date('date_returned')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Financial Information
            |--------------------------------------------------------------------------
            */
            $table->decimal('repair_cost', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */
            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('item_id');
            $table->index('employee_id');
            $table->index('status');
            $table->index('date_sent');
            $table->index('date_returned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};