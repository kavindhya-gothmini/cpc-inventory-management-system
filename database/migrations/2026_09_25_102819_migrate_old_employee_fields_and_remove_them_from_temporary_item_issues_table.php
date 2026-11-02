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
        | Preserve existing receiving employee information
        |--------------------------------------------------------------------------
        |
        | The old receiving_employee_id represents the employee associated
        | with the existing received record. Move that information into
        | returning_employee_id before removing the old column.
        |
        */

        if (Schema::hasColumn('temporary_item_issues', 'receiving_employee_id')) {
            DB::statement('
                UPDATE temporary_item_issues
                SET returning_employee_id = receiving_employee_id
                WHERE returning_employee_id IS NULL
                  AND receiving_employee_id IS NOT NULL
            ');
        }

        /*
        |--------------------------------------------------------------------------
        | Remove obsolete receiving employee column
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('temporary_item_issues', 'receiving_employee_id')) {
            Schema::table('temporary_item_issues', function (Blueprint $table) {
                $table->dropForeign(['receiving_employee_id']);
                $table->dropColumn('receiving_employee_id');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | transporting_employee_id
        |--------------------------------------------------------------------------
        |
        | This column has already been removed from the database,
        | so there is nothing to remove here.
        |
        */
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Restore receiving employee column
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('temporary_item_issues', 'receiving_employee_id')) {
            Schema::table('temporary_item_issues', function (Blueprint $table) {
                $table->foreignId('receiving_employee_id')
                    ->nullable()
                    ->after('returning_employee_id')
                    ->constrained('employees')
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Restore previous data
        |--------------------------------------------------------------------------
        */

        DB::statement('
            UPDATE temporary_item_issues
            SET receiving_employee_id = returning_employee_id
            WHERE returning_employee_id IS NOT NULL
        ');
    }
};

