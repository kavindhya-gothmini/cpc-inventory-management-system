<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FunctionUnitSearchTest extends TestCase
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

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('source_function_id')->nullable();
            $table->timestamps();
        });

        Schema::create('function_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('function_units');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    private function createUser(string $role = 'super_admin'): User
    {
        return User::query()->create([
            'name' => 'Function Unit Tester',
            'email' => strtolower(str_replace('_', '-', $role)) . '@example.test',
            'password' => 'password',
            'role' => $role,
        ]);
    }

    public function test_partial_search_matches_name_code_and_department(): void
    {
        $user = $this->createUser();

        $departmentId = DB::table('departments')->insertGetId([
            'name' => 'Engineering',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('function_units')->insert([
            [
                'name' => 'Network Operations',
                'code' => 'NET-101',
                'department_id' => $departmentId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Payroll',
                'code' => 'PAY-202',
                'department_id' => $departmentId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', ['search' => 'work']))
            ->assertOk()
            ->assertSee('Network Operations')
            ->assertDontSee('Payroll');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', ['search' => 'PAY-']))
            ->assertOk()
            ->assertSee('Payroll')
            ->assertDontSee('Network Operations');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', ['search' => 'Engin']))
            ->assertOk()
            ->assertSee('Network Operations')
            ->assertSee('Payroll');
    }

    public function test_search_and_status_filters_can_be_combined(): void
    {
        $user = $this->createUser();
        $departmentId = DB::table('departments')->insertGetId([
            'name' => 'Security',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $supportDepartmentId = DB::table('departments')->insertGetId([
            'name' => 'Support',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('function_units')->insert([
            [
                'name' => 'Security Operations',
                'code' => 'SEC-1',
                'department_id' => $departmentId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Security Archive',
                'code' => 'SEC-2',
                'department_id' => $departmentId,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Support Desk',
                'code' => 'SUP-1',
                'department_id' => $supportDepartmentId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', [
                'search' => 'Security',
                'status' => 'active',
            ]))
            ->assertOk()
            ->assertSee('Security Operations')
            ->assertDontSee('Security Archive')
            ->assertDontSee('Support Desk');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', [
                'search' => 'Security',
                'status' => 'inactive',
            ]))
            ->assertOk()
            ->assertSee('Security Archive')
            ->assertDontSee('Security Operations');
    }

    public function test_empty_invalid_and_unusually_long_search_inputs_are_handled(): void
    {
        $user = $this->createUser();

        DB::table('departments')->insert([
            'name' => 'Operations',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $departmentId = DB::table('departments')->where('name', 'Operations')->value('id');

        DB::table('function_units')->insert([
            'name' => 'Operations Unit',
            'code' => 'OPS-1',
            'department_id' => $departmentId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', ['search' => '']))
            ->assertOk()
            ->assertSee('Operations Unit');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', ['search' => 'NOT-A-REAL-UNIT']))
            ->assertOk()
            ->assertDontSee('Operations Unit');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', [
                'search' => str_repeat('a', 101),
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('search');

        $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', [
                'status' => 'invalid-status',
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('status');
    }

    public function test_pagination_preserves_search_and_status_parameters(): void
    {
        $user = $this->createUser();
        $departmentId = DB::table('departments')->insertGetId([
            'name' => 'Infrastructure',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($number = 1; $number <= 12; $number++) {
            DB::table('function_units')->insert([
                'name' => 'Infrastructure Unit ' . $number,
                'code' => 'INF-' . $number,
                'department_id' => $departmentId,
                'is_active' => true,
                'created_at' => now()->subMinutes(13 - $number),
                'updated_at' => now()->subMinutes(13 - $number),
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get(route('admin.function-units.index', [
                'search' => 'Infrastructure',
                'status' => 'active',
                'page' => 2,
            ]));

        $response
            ->assertOk()
            ->assertViewHas('functionUnits', function ($units) {
                return $units->currentPage() === 2
                    && $units->count() === 2
                    && $units->total() === 12
                    && str_contains(
                        $units->url(1),
                        'search=Infrastructure'
                    )
                    && str_contains(
                        $units->url(1),
                        'status=active'
                    );
            });
    }

    public function test_admin_and_employee_cannot_manage_function_units(): void
    {
        $functionUnitId = DB::table('function_units')->insertGetId([
            'name' => 'Protected Unit',
            'code' => 'PROT-1',
            'department_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (['admin', 'employee'] as $role) {
            $user = $this->createUser($role);

            $this
                ->actingAs($user)
                ->delete(route('admin.function-units.destroy', $functionUnitId))
                ->assertForbidden();
        }

        $this->assertDatabaseHas('function_units', [
            'id' => $functionUnitId,
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_deactivate_function_unit(): void
    {
        $user = $this->createUser('super_admin');
        $functionUnitId = DB::table('function_units')->insertGetId([
            'name' => 'Deactivatable Unit',
            'code' => 'DEACT-1',
            'department_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->delete(route('admin.function-units.destroy', $functionUnitId))
            ->assertRedirect(route('admin.function-units.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('function_units', [
            'id' => $functionUnitId,
            'is_active' => false,
        ]);
    }
}
