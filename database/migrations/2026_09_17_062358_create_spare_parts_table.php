<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('part_code', 100)
                ->unique();

            $table->string('part_name', 200);

            $table->string('category', 150)
                ->nullable();

            $table->text('description')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Supplier Information
            |--------------------------------------------------------------------------
            */
            $table->string('supplier_name', 200)
                ->nullable();

            $table->string('supplier_reference', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Stock Information
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('quantity')
                ->default(0);

            $table->unsignedInteger('minimum_stock')
                ->default(0);

            $table->decimal('unit_price', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Warranty
            |--------------------------------------------------------------------------
            */
            $table->date('warranty_start')
                ->nullable();

            $table->date('warranty_end')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Storage
            |--------------------------------------------------------------------------
            */
            $table->string('location', 200)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('part_name');
            $table->index('category');
            $table->index('supplier_name');
            $table->index('status');
            $table->index('warranty_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};