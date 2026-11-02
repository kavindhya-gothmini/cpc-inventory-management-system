<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_part_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spare_part_supplier_id')
                ->nullable()
                ->constrained('spare_part_suppliers')
                ->nullOnDelete();

            $table->string('document_type', 100);

            $table->string('document_number', 100)->nullable();

            $table->string('document_title', 200);

            $table->date('document_date')->nullable();

            $table->string('file_name', 255);

            $table->string('file_path', 500);

            $table->string('file_type', 100)->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('document_type');
            $table->index('document_date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_part_documents');
    }
};