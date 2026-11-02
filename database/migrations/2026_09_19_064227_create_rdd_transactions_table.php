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
        Schema::create('rdd_transactions', function (Blueprint $table) {
            $table->id();

            /*
             * RDD Reference
             * Example: RDD-2026-0001
             */
            $table->string('reference_no', 50)->unique();

            /*
             * Inventory item being sent to RDD.
             */
            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Employee responsible for / associated with the item.
             */
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            /*
             * Date the item was sent to RDD.
             */
            $table->date('rdd_date');

            /*
             * A valid reason is mandatory.
             */
            $table->text('reason');

            /*
             * Recommendation made when sending the item.
             */
            $table->text('recommendation')->nullable();

            /*
             * Additional information.
             */
            $table->text('remarks')->nullable();

            /*
             * RDD workflow status.
             *
             * pending
             * approved
             * dispatched
             * received
             * inspection
             * in_progress
             * completed
             * returned
             * beyond_repair
             * rejected
             * cancelled
             */
            $table->string('status', 30)->default('pending')->index();

            /*
             * Final decision/outcome.
             *
             * return
             * repair
             * replace
             * retain
             * dispose
             */
            $table->string('decision', 30)->nullable();

            /*
             * Date received by RDD.
             */
            $table->date('received_date')->nullable();

            /*
             * Date the RDD process was completed.
             */
            $table->date('completed_date')->nullable();

            /*
             * Information about the RDD work/outcome.
             */
            $table->text('work_performed')->nullable();

            /*
             * Final condition/status of the equipment.
             */
            $table->string('final_condition', 100)->nullable();

            /*
             * Prevent creating another active RDD transaction
             * for the same item through application logic.
             */
            $table->boolean('is_active')->default(true)->index();

            /*
             * User who created the RDD transaction.
             *
             * We keep this nullable because your existing users/auth
             * structure should not be changed by this module.
             */
            $table->unsignedBigInteger('created_by')->nullable()->index();

            /*
             * User who last updated the RDD transaction.
             */
            $table->unsignedBigInteger('updated_by')->nullable()->index();

            $table->timestamps();

            /*
             * Useful index for searching/filtering RDD records.
             */
            $table->index(['item_id', 'status']);
            $table->index(['rdd_date', 'status']);
            $table->index(['employee_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdd_transactions');
    }
};