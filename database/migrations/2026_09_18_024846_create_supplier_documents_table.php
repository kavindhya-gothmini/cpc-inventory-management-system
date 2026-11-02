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
        Schema::create('supplier_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spare_part_supplier_id')
                ->constrained('spare_part_suppliers')
                ->cascadeOnDelete();

            $table->string('document_name', 150);

            $table->string('document_type', 100)
                ->nullable();

            $table->string('file_path');

            $table->string('original_filename')
                ->nullable();

            $table->string('mime_type', 100)
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_documents');
    }
};
