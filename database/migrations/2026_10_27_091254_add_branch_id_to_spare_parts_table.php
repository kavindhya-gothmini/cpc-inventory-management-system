<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Add branch ownership to spare-part stock.
     */
    public function up(): void
    {
        // Add the column as nullable temporarily so existing records
        // can be assigned to their correct branches.
        if (! Schema::hasColumn('spare_parts', 'branch_id')) {
            Schema::table('spare_parts', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')
                    ->nullable()
                    ->after('id');
            });
        }

        // Preserve existing IDs and assign the confirmed ownership.
        $headOfficePart = DB::table('spare_parts')
            ->where('id', 3)
            ->where('part_code', 'ram-1009')
            ->first();

        if ($headOfficePart) {
            DB::table('spare_parts')
                ->where('id', 3)
                ->update(['branch_id' => 4]);
        }

        $refineryPart = DB::table('spare_parts')
            ->where('id', 4)
            ->where('part_code', 'ram-1006')
            ->first();

        if ($refineryPart) {
            DB::table('spare_parts')
                ->where('id', 4)
                ->update(['branch_id' => 11]);
        }

        // Never silently assign unexpected records to a branch.
        $unassignedIds = DB::table('spare_parts')
            ->whereNull('branch_id')
            ->pluck('id')
            ->all();

        if (! empty($unassignedIds)) {
            throw new \RuntimeException(
                'Cannot finish spare-parts branch migration. '
                . 'Assign the following spare-part IDs to their '
                . 'confirmed branches first: '
                . implode(', ', $unassignedIds)
            );
        }

        // Change part-code uniqueness from global to per branch.
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropUnique('spare_parts_part_code_unique');

            $table->unsignedBigInteger('branch_id')
                ->nullable(false)
                ->change();

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->restrictOnDelete();

            $table->unique(
                ['branch_id', 'part_code'],
                'spare_parts_branch_part_code_unique'
            );
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);

            $table->dropUnique(
                'spare_parts_branch_part_code_unique'
            );

            $table->dropColumn('branch_id');

            $table->unique(
                'part_code',
                'spare_parts_part_code_unique'
            );
        });
    }
};
