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
        Schema::create('repair_collections', function (Blueprint $table) {
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
            | Person who collected / handed over the item
            |--------------------------------------------------------------------------
            */
            $table->string('collector_name', 150);

            $table->string('collector_epf_no', 50)->nullable();

            $table->string('collector_contact', 50)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Date the item was collected / handed over for repair
            |--------------------------------------------------------------------------
            */
            $table->date('issued_date');

            /*
            |--------------------------------------------------------------------------
            | Additional information
            |--------------------------------------------------------------------------
            */
            $table->text('remarks')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */
            $table->index('repair_id');
            $table->index('collector_epf_no');
            $table->index('issued_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_collections');
    }
};