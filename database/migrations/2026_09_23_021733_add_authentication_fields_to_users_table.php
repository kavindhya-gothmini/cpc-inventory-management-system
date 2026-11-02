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
        Schema::table('users', function (Blueprint $table) {
            $table->string('epf_number', 30)
                ->nullable()
                ->unique()
                ->after('id');

            $table->string('phone', 20)
                ->nullable()
                ->after('email');

            $table->string('role', 20)
                ->default('employee')
                ->after('phone');
        });

        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('phone', 20)->index();
            $table->enum('delivery_method', ['email', 'phone']);
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_codes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['epf_number']);
            $table->dropColumn([
                'epf_number',
                'phone',
                'role',
            ]);
        });
    }
};