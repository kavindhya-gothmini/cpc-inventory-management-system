<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_asset_value_mappings', function (Blueprint $table) {
            $table->id();

            $table->string('source_system', 100);
            $table->string('mapping_type', 30);
            $table->string('source_value', 255);

            $table->foreignId('local_item_type_id')
                ->nullable()
                ->constrained('item_types')
                ->nullOnDelete();

            $table->foreignId('local_branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            $table->string('local_status', 50)->nullable();

            $table->boolean('is_approved')->default(false);
            $table->string('approved_by', 150)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['source_system', 'mapping_type', 'source_value'],
                'external_asset_value_mapping_unique'
            );

            $table->index(['mapping_type', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_asset_value_mappings');
    }
};
