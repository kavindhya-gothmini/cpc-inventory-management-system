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
        Schema::create('item_models', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Make
            |--------------------------------------------------------------------------
            |
            | Each model belongs to one Make.
            |
            | Example:
            | HP -> ProBook 450 G9
            |
            */
            $table->foreignId('make_id')
                ->constrained('makes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Model Name
            |--------------------------------------------------------------------------
            */
            $table->string('name', 150);

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate model names under the same make
            |--------------------------------------------------------------------------
            |
            | HP -> ProBook 450 G9
            | HP -> ProBook 450 G9  ❌ duplicate
            |
            | Dell -> ProBook 450 G9  ✅ allowed
            |
            */
            $table->unique(
                ['make_id', 'name'],
                'item_models_make_id_name_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_models');
    }
};