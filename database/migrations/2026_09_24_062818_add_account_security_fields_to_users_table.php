<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add account security fields.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Account access status
            $table->boolean('is_locked')
                ->default(false)
                ->after('role');

            // Failed login attempt counter
            $table->unsignedTinyInteger('failed_login_attempts')
                ->default(0)
                ->after('is_locked');

            // When the account was locked
            $table->timestamp('locked_at')
                ->nullable()
                ->after('failed_login_attempts');
        });
    }

    /**
     * Remove account security fields.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'is_locked',
                'failed_login_attempts',
                'locked_at',
            ]);
        });
    }
};