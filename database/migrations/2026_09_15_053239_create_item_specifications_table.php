<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_specifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->unique()
                ->constrained('items')
                ->cascadeOnDelete();

            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('processor')->nullable();
            $table->string('graphics_card')->nullable();
            $table->string('operating_system')->nullable();

            $table->text('additional_specifications')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_specifications');
    }
};