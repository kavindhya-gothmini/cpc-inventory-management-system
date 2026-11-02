
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fault_reports', function (Blueprint $table) {
            $table->id();

            // User who submitted the report
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Location responsible for handling the report
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            // Device identification
            $table->string('asset_number', 100)->nullable();
            $table->string('device_type', 50);
            $table->string('device_make', 100)->nullable();
            $table->string('device_model', 100)->nullable();

            // Fault description and urgency
            $table->string('title', 150);
            $table->text('description');
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'critical',
            ])->default('medium');

            // Workflow status
            $table->enum('status', [
                'submitted',
                'under_review',
                'in_progress',
                'waiting_for_user',
                'resolved',
                'closed',
            ])->default('submitted');

            // IT staff member handling the report
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // IT response and resolution details
            $table->text('it_response')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['branch_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fault_reports');
    }
};
