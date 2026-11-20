<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InventoryItemSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
        });

        foreach ([
            'item_types',
            'makes',
            'item_models',
            'branches',
            'departments',
            'function_units',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('epf_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_type_id');
            $table->string('asset_number');
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('make_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('workstation_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('function_unit_id')->nullable();
            $table->unsignedBigInteger('current_employee_id')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('employees');

        foreach ([
            'function_units',
            'departments',
            'branches',
            'item_models',
            'makes',
            'item_types',
            'users',
        ] as $tableName) {
            Schema::dropIfExists($tableName);
        }

        parent::tearDown();
    }

    public function test_search_matches_inventory_items_beyond_the_current_page(): void
    {
        $user = User::query()->create([
            'name' => 'Search Tester',
            'email' => 'search@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Laptop',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($itemNumber = 1; $itemNumber <= 11; $itemNumber++) {
            DB::table('items')->insert([
                'item_type_id' => $itemTypeId,
                'asset_number' => 'ASSET-' . $itemNumber,
                'serial_number' => 'SERIAL-' . $itemNumber,
                'make' => 'CPC',
                'model' => 'Model ' . $itemNumber,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', ['search' => 'ASSET-11']));

        $response
            ->assertOk()
            ->assertSee('ASSET-11')
            ->assertDontSee('ASSET-10');
    }

    public function test_search_rejects_values_longer_than_one_hundred_characters(): void
    {
        $user = User::query()->create([
            'name' => 'Validation Tester',
            'email' => 'validation@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => str_repeat('a', 101),
            ]));

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('search');
    }
}
