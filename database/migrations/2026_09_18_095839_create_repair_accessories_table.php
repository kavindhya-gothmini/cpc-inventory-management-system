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
        Schema::create('repair_accessories', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Related Repair
            |--------------------------------------------------------------------------
            */
            $table->foreignId('repair_id')
                ->constrained('repairs')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Accessory Information
            |--------------------------------------------------------------------------
            */
            $table->string('accessory_name', 150);

            $table->unsignedInteger('quantity')->default(1);

            /*
            |--------------------------------------------------------------------------
            | Accessory Issue Information
            |--------------------------------------------------------------------------
            */
            $table->date('issued_date');

            /*
            |--------------------------------------------------------------------------
            | Accessory Return Information
            |--------------------------------------------------------------------------
            */
            $table->boolean('returned')->default(false);

            $table->date('returned_date')->nullable();

            $table->string('returned_by', 150)->nullable();

            $table->text('return_remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | General Timestamps
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('repair_id');
            $table->index('accessory_name');
            $table->index('issued_date');
            $table->index('returned');
            $table->index('returned_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_accessories');
    }
};