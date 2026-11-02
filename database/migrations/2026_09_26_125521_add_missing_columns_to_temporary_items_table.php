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
        Schema::table('temporary_items', function (Blueprint $table) {
            $table->string('item_code')
                ->unique()
                ->after('id');

            $table->string('item_type')
                ->after('item_code');

            $table->text('description')
                ->nullable()
                ->after('item_type');

            $table->enum('status', [
                'available',
                'issued',
                'inactive',
            ])
                ->default('available')
                ->after('description');

            $table->text('remarks')
                ->nullable()
                ->after('status');

            $table->foreignId('created_by')
                ->nullable()
                ->after('remarks')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->after('created_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('status');
            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_items', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropUnique(['item_code']);

            $table->dropIndex(['status']);
            $table->dropIndex(['item_type']);

            $table->dropColumn([
                'item_code',
                'item_type',
                'description',
                'status',
                'remarks',
                'created_by',
                'updated_by',
            ]);
        });
    }
};

