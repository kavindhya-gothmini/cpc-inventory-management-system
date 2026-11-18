<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedInteger('source_location_id')
                ->nullable()
                ->unique()
                ->after('branch_id');
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['source_location_id']);
            $table->dropColumn('source_location_id');
        });
    }
};
