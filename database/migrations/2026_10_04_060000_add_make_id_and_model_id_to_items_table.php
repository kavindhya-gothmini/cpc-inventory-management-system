<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Make sure the columns exist
        |--------------------------------------------------------------------------
        */

        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'make_id')) {
                $table->unsignedBigInteger('make_id')
                    ->nullable()
                    ->after('serial_number');
            }

            if (!Schema::hasColumn('items', 'model_id')) {
                $table->unsignedBigInteger('model_id')
                    ->nullable()
                    ->after('make_id');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Check existing foreign keys
        |--------------------------------------------------------------------------
        */

        $foreignKeys = DB::select("
            SELECT
                CONSTRAINT_NAME,
                COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'items'
              AND REFERENCED_TABLE_NAME IS NOT NULL
              AND COLUMN_NAME IN ('make_id', 'model_id')
        ");

        $existingForeignKeys = collect($foreignKeys)
            ->keyBy('COLUMN_NAME');

        /*
        |--------------------------------------------------------------------------
        | Add Make foreign key only if it does not already exist
        |--------------------------------------------------------------------------
        */

        if (!$existingForeignKeys->has('make_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreign('make_id', 'items_make_id_foreign')
                    ->references('id')
                    ->on('makes')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Add Model foreign key only if it does not already exist
        |--------------------------------------------------------------------------
        */

        if (!$existingForeignKeys->has('model_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreign('model_id', 'items_model_id_foreign')
                    ->references('id')
                    ->on('item_models')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get existing foreign keys
        |--------------------------------------------------------------------------
        */

        $foreignKeys = DB::select("
            SELECT
                CONSTRAINT_NAME,
                COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'items'
              AND REFERENCED_TABLE_NAME IS NOT NULL
              AND COLUMN_NAME IN ('make_id', 'model_id')
        ");

        foreach ($foreignKeys as $foreignKey) {
            Schema::table('items', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Remove columns if they exist
        |--------------------------------------------------------------------------
        */

        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'model_id')) {
                $table->dropColumn('model_id');
            }

            if (Schema::hasColumn('items', 'make_id')) {
                $table->dropColumn('make_id');
            }
        });
    }
};