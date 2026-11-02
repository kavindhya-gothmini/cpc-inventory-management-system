<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Convert any existing "disabled" records to the
         * new RDD-compatible status before changing the ENUM.
         *
         * This prevents existing records from becoming invalid.
         */
        DB::table('items')
            ->where('status', 'disabled')
            ->update([
                'status' => 'available',
            ]);

        /*
         * Update the status ENUM to support the complete
         * inventory workflow including RDD.
         */
        DB::statement("
            ALTER TABLE items
            MODIFY status ENUM(
                'available',
                'in_use',
                'under_repair',
                'sent_to_rdd'
            ) NOT NULL DEFAULT 'available'
        ");
    }

    public function down(): void
    {
        /*
         * Any RDD items must first be converted back to
         * an existing legacy status before removing
         * sent_to_rdd from the ENUM.
         */
        DB::table('items')
            ->where('status', 'sent_to_rdd')
            ->update([
                'status' => 'available',
            ]);

        DB::statement("
            ALTER TABLE items
            MODIFY status ENUM(
                'available',
                'in_use',
                'under_repair',
                'disabled'
            ) NOT NULL DEFAULT 'available'
        ");
    }
};