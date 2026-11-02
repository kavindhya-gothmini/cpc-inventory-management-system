<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Extend the local inventory status enum.
     *
     * This migration affects the local MySQL database only.
     */
    public function up(): void
    {
        DB::connection('mysql')->statement("
            ALTER TABLE `items`
            MODIFY `status`
            ENUM(
                'available',
                'in_use',
                'under_repair',
                'sent_to_rdd',
                'scrapped',
                'transferred'
            )
            NOT NULL DEFAULT 'available'
        ");
    }

    /**
     * Prevent rollback if records still use the new statuses.
     */
    public function down(): void
    {
        $hasNewStatuses = DB::connection('mysql')
            ->table('items')
            ->whereIn('status', ['scrapped', 'transferred'])
            ->exists();

        if ($hasNewStatuses) {
            throw new RuntimeException(
                'Cannot remove scrapped/transferred statuses while '
                . 'inventory records still use them.'
            );
        }

        DB::connection('mysql')->statement("
            ALTER TABLE `items`
            MODIFY `status`
            ENUM(
                'available',
                'in_use',
                'under_repair',
                'sent_to_rdd'
            )
            NOT NULL DEFAULT 'available'
        ");
    }
};