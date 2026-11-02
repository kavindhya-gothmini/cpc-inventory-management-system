<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_part_suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('supplier_code', 50)->unique();
            $table->string('supplier_name', 150);

            $table->string('contact_person', 150)->nullable();

            $table->string('phone', 30)->nullable();
            $table->string('alternative_phone', 30)->nullable();

            $table->string('email', 150)->nullable();

            $table->text('address')->nullable();

            $table->string('city', 100)->nullable();

            $table->string('supplier_type', 100)
                ->nullable()
                ->comment('Manufacturer, Distributor, Dealer, Service Provider, Other');

            $table->text('notes')->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('supplier_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_part_suppliers');
    }
};