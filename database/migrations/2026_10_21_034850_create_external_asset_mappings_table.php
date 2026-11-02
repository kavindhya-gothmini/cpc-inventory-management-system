<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_asset_mappings', function (Blueprint $table) {
            $table->id();

            // Identifies the source system and its original asset ID.
            $table->string('source_system', 100);
            $table->string('source_asset_id', 100);

            // Links to the corresponding local inventory item.
            $table->foreignId('item_id')
                ->nullable()
                ->constrained('items')
                ->nullOnDelete();

            // Records when the source record was last synchronised.
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            // A source asset must have only one mapping.
            $table->unique(
                ['source_system', 'source_asset_id'],
                'external_asset_source_unique'
            );

            // A local item should not be mapped twice to the same source.
            $table->unique(
                ['source_system', 'item_id'],
                'external_asset_item_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_asset_mappings');
    }
};
