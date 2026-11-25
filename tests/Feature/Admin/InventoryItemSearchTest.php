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

        Schema::create('item_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->timestamps();
        });

        Schema::create('item_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('function_unit_id')->nullable();
            $table->timestamps();
        });

        Schema::create('item_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('item_histories');
        Schema::dropIfExists('item_assignments');
        Schema::dropIfExists('item_specifications');
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

    public function test_search_matches_partial_inventory_terms(): void
    {
        $user = User::query()->create([
            'name' => 'Partial Search Tester',
            'email' => 'partial-search@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Laptop',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $desktopItemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Desktop',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'ASSET-LAPTOP-100',
                'serial_number' => 'SN-LAPTOP-100',
                'make' => 'CPC',
                'model' => 'Latitude',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_type_id' => $desktopItemTypeId,
                'asset_number' => 'ASSET-DESKTOP-200',
                'serial_number' => 'SN-DESKTOP-200',
                'make' => 'CPC',
                'model' => 'OptiPlex',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', ['search' => 'LAPT']));

        $response
            ->assertOk()
            ->assertSee('ASSET-LAPTOP-100')
            ->assertDontSee('ASSET-DESKTOP-200');
    }

    public function test_empty_search_returns_inventory_items(): void
    {
        $user = User::query()->create([
            'name' => 'Empty Search Tester',
            'email' => 'empty-search@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Printer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'ASSET-EMPTY-1',
                'serial_number' => 'SERIAL-EMPTY-1',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'ASSET-EMPTY-2',
                'serial_number' => 'SERIAL-EMPTY-2',
                'status' => 'in_use',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', ['search' => '']));

        $response
            ->assertOk()
            ->assertSee('ASSET-EMPTY-1')
            ->assertSee('ASSET-EMPTY-2');
    }

    public function test_invalid_search_term_returns_no_matches(): void
    {
        $user = User::query()->create([
            'name' => 'Invalid Search Tester',
            'email' => 'invalid-search@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Scanner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            'item_type_id' => $itemTypeId,
            'asset_number' => 'ASSET-SCANNER-300',
            'serial_number' => 'SERIAL-SCANNER-300',
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', ['search' => 'DOES-NOT-EXIST']));

        $response
            ->assertOk()
            ->assertDontSee('ASSET-SCANNER-300');
    }

    public function test_search_rejects_non_string_input(): void
    {
        $user = User::query()->create([
            'name' => 'Type Validation Tester',
            'email' => 'type-validation@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => ['unexpected', 'array'],
            ]));

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('search');
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
                'search' => str_repeat('a', 1000),
            ]));

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('search');
    }

    public function test_search_pagination_returns_results_from_the_requested_page(): void
    {
        $user = User::query()->create([
            'name' => 'Pagination Tester',
            'email' => 'pagination@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Server',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($itemNumber = 1; $itemNumber <= 12; $itemNumber++) {
            DB::table('items')->insert([
                'item_type_id' => $itemTypeId,
                'asset_number' => 'PAGE-ASSET-' . $itemNumber,
                'serial_number' => 'PAGE-SERIAL-' . $itemNumber,
                'status' => 'available',
                'created_at' => now()->subMinutes(13 - $itemNumber),
                'updated_at' => now()->subMinutes(13 - $itemNumber),
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => 'PAGE-ASSET',
                'page' => 2,
            ]));

        $response
            ->assertOk()
            ->assertViewHas('items', function ($items) {
                return $items->currentPage() === 2
                    && $items->count() === 2
                    && $items->total() === 12;
            });
    }

    public function test_search_combines_with_status_search_on_paginated_results(): void
    {
        $user = User::query()->create([
            'name' => 'Combined Filter Tester',
            'email' => 'combined-filter@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Workstation',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('items')->insert([
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'FILTER-ASSET-1',
                'serial_number' => 'FILTER-SERIAL-1',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'FILTER-ASSET-2',
                'serial_number' => 'FILTER-SERIAL-2',
                'status' => 'in_use',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_type_id' => $itemTypeId,
                'asset_number' => 'OTHER-ASSET-3',
                'serial_number' => 'FILTER-SERIAL-3',
                'status' => 'in_use',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => 'FILTER-ASSET',
            ]));

        $response
            ->assertOk()
            ->assertSee('FILTER-ASSET-1')
            ->assertSee('FILTER-ASSET-2')
            ->assertDontSee('OTHER-ASSET-3');

        $statusResponse = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => 'in use',
            ]));

        $statusResponse
            ->assertOk()
            ->assertSee('FILTER-ASSET-2')
            ->assertSee('OTHER-ASSET-3')
            ->assertDontSee('FILTER-ASSET-1');
    }

    public function test_branch_scoped_user_cannot_search_or_view_another_branch_item(): void
    {
        $authorizedBranchId = DB::table('branches')->insertGetId([
            'name' => 'Authorized Branch',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $unauthorizedBranchId = DB::table('branches')->insertGetId([
            'name' => 'Unauthorized Branch',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::query()->create([
            'name' => 'Branch Scoped Tester',
            'email' => 'branch-scoped@example.test',
            'password' => 'password',
            'role' => 'admin',
            'branch_id' => $authorizedBranchId,
        ]);

        $itemTypeId = DB::table('item_types')->insertGetId([
            'name' => 'Laptop',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $authorizedItemId = DB::table('items')->insertGetId([
            'item_type_id' => $itemTypeId,
            'asset_number' => 'AUTHORIZED-ASSET',
            'serial_number' => 'AUTHORIZED-SERIAL',
            'branch_id' => $authorizedBranchId,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $unauthorizedItemId = DB::table('items')->insertGetId([
            'item_type_id' => $itemTypeId,
            'asset_number' => 'SECRET-BRANCH-ASSET',
            'serial_number' => 'SECRET-BRANCH-SERIAL',
            'branch_id' => $unauthorizedBranchId,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $searchResponse = $this
            ->actingAs($user)
            ->get(route('admin.items.index', [
                'search' => 'ASSET',
            ]));

        $searchResponse
            ->assertOk()
            ->assertSee('AUTHORIZED-ASSET')
            ->assertDontSee('SECRET-BRANCH-ASSET');

        $this
            ->actingAs($user)
            ->get(route('admin.items.show', $unauthorizedItemId))
            ->assertNotFound();

        $this
            ->actingAs($user)
            ->get(route('admin.items.show', $authorizedItemId))
            ->assertOk();
    }
}
