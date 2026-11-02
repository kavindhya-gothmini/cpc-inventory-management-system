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
        Schema::create('rdd_histories', function (Blueprint $table) {
            $table->id();

            /*
             * Main RDD transaction.
             */
            $table->foreignId('rdd_transaction_id')
                ->constrained('rdd_transactions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            /*
             * Previous and new status.
             */
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30);

            /*
             * Explanation for the status change.
             */
            $table->text('remarks')->nullable();

            /*
             * Optional recommendation/outcome information.
             */
            $table->text('recommendation')->nullable();

            /*
             * User who performed the action.
             */
            $table->unsignedBigInteger('changed_by')->nullable()->index();

            $table->timestamps();

            /*
             * Fast history retrieval.
             */
            $table->index([
                'rdd_transaction_id',
                'created_at'
            ]);

            $table->index([
                'new_status',
                'created_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdd_histories');
    }
};