<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_spare_parts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Repair
            |--------------------------------------------------------------------------
            */
            $table->foreignId('repair_id')
                ->constrained('repairs')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Spare Part
            |--------------------------------------------------------------------------
            */
            $table->foreignId('spare_part_id')
                ->constrained('spare_parts')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Quantity Used
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('quantity_used');

            /*
            |--------------------------------------------------------------------------
            | Cost Snapshot
            |--------------------------------------------------------------------------
            | Stores the unit price at the time of usage.
            | This prevents old repair costs changing when the
            | spare part's current price changes.
            |--------------------------------------------------------------------------
            */
            $table->decimal('unit_price', 12, 2)
                ->default(0);

            $table->decimal('total_price', 12, 2)
                ->default(0);

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('repair_id');
            $table->index('spare_part_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_spare_parts');
    }
};