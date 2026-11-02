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
        Schema::create('makes', function (Blueprint $table) {
            $table->id();

            /*
             * Example:
             * HP
             * Dell
             * Lenovo
             * Acer
             */
            $table->string('name', 100)->unique();

            /*
             * Optional description for the Make.
             */
            $table->text('description')->nullable();

            /*
             * Active / inactive master record.
             *
             * Only Super Admin will be able to change this.
             */
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makes');
    }
};