
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename legacy inventory columns to the new professional names.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('asset_no', 'asset_number');
            $table->renameColumn('serial_no', 'serial_number');
        });
    }

    /**
     * Restore the previous column names if the migration is rolled back.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('asset_number', 'asset_no');
            $table->renameColumn('serial_number', 'serial_no');
        });
    }
};

