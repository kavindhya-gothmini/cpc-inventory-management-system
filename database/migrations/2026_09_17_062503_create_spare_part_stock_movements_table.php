<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_part_stock_movements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('spare_part_id')
                ->constrained('spare_parts')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Movement Type
            |--------------------------------------------------------------------------
            */
            $table->enum('movement_type', [
                'stock_in',
                'used_for_repair',
                'adjustment',
                'return',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Quantity Information
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('quantity');

            $table->unsignedInteger('quantity_before');

            $table->unsignedInteger('quantity_after');

            /*
            |--------------------------------------------------------------------------
            | Related Repair
            |--------------------------------------------------------------------------
            */
            $table->foreignId('repair_id')
                ->nullable()
                ->constrained('repairs')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Reference / Notes
            |--------------------------------------------------------------------------
            */
            $table->string('reference', 150)
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            $table->index('spare_part_id');
            $table->index('movement_type');
            $table->index('repair_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_part_stock_movements');
    }
};