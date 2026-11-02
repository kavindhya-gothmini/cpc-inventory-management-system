
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpc_master_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('source_location_id')->unique();
            $table->string('location_name')->nullable();
            $table->boolean('is_available')->nullable();
            $table->string('ibm_location')->nullable();
            $table->unsignedBigInteger('local_branch_id')->nullable()->index();
            $table->json('source_payload')->nullable();
            $table->timestamp('source_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cpc_master_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('source_function_id')->unique();
            $table->string('function_name')->nullable();
            $table->boolean('is_available')->nullable();
            $table->json('source_payload')->nullable();
            $table->timestamp('source_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cpc_master_designations', function (Blueprint $table) {
            $table->id();

            // HAPIS uses IDs such as D000 and D001.
            $table->string('source_designation_id', 32)->unique();
            $table->string('designation_name')->nullable();
            $table->boolean('is_available')->nullable();
            $table->json('source_payload')->nullable();
            $table->timestamp('source_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cpc_master_sub_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('source_sub_function_id')->unique();
            $table->string('sub_function_name')->nullable();
            $table->unsignedInteger('source_function_id')->nullable()->index();
            $table->unsignedInteger('source_location_id')->nullable()->index();
            $table->string('cost_centre')->nullable();
            $table->string('fund')->nullable();
            $table->string('ibm_code')->nullable();
            $table->boolean('is_available')->nullable();
            $table->json('source_payload')->nullable();
            $table->timestamp('source_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cpc_master_employees', function (Blueprint $table) {
            $table->id();
            $table->string('source_epf_no', 32)->unique();
            $table->string('name_with_initials')->nullable();
            $table->string('full_name')->nullable();
            $table->boolean('is_available')->nullable();
            $table->unsignedInteger('source_sub_function_id')->nullable()->index();
            $table->string('source_designation_id', 32)->nullable()->index();
            $table->unsignedInteger('source_location_id')->nullable()->index();
            $table->unsignedInteger('source_function_id')->nullable()->index();
            $table->json('source_payload')->nullable();
            $table->timestamp('source_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpc_master_employees');
        Schema::dropIfExists('cpc_master_sub_functions');
        Schema::dropIfExists('cpc_master_designations');
        Schema::dropIfExists('cpc_master_functions');
        Schema::dropIfExists('cpc_master_locations');
    }
};
