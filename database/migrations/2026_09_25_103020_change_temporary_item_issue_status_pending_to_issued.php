<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Step 1: Temporarily allow both pending and issued
        |--------------------------------------------------------------------------
        |
        | We must allow "issued" before converting existing "pending"
        | records to "issued".
        |
        */

        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'issued',
                'received',
                'cancelled',
            ])
                ->default('issued')
                ->change();
        });

        /*
        |--------------------------------------------------------------------------
        | Step 2: Convert existing pending records to issued
        |--------------------------------------------------------------------------
        */

        DB::table('temporary_item_issues')
            ->where('status', 'pending')
            ->update([
                'status' => 'issued',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Step 3: Remove pending from the enum
        |--------------------------------------------------------------------------
        */

        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->enum('status', [
                'issued',
                'received',
                'cancelled',
            ])
                ->default('issued')
                ->change();
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Step 1: Temporarily allow both issued and pending
        |--------------------------------------------------------------------------
        */

        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'issued',
                'received',
                'cancelled',
            ])
                ->default('pending')
                ->change();
        });

        /*
        |--------------------------------------------------------------------------
        | Step 2: Convert issued records back to pending
        |--------------------------------------------------------------------------
        */

        DB::table('temporary_item_issues')
            ->where('status', 'issued')
            ->update([
                'status' => 'pending',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Step 3: Restore original enum
        |--------------------------------------------------------------------------
        */

        Schema::table('temporary_item_issues', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'received',
                'cancelled',
            ])
                ->default('pending')
                ->change();
        });
    }
};

