<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\FunctionUnit;
use App\Services\CpcMasterDataSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunctionUnitController extends Controller
{
    /**
     * Display a listing of Function Units.
     *
     * All authenticated roles can view Function Units.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:all,active,inactive'],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $status = $validated['status'] ?? 'all';

        $functionUnits = FunctionUnit::with('department')
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $searchTerm = '%' . $search . '%';

                    $query->where(function ($searchQuery) use ($searchTerm) {
                        $searchQuery
                            ->where('name', 'like', $searchTerm)
                            ->orWhere('code', 'like', $searchTerm)
                            ->orWhereHas(
                                'department',
                                fn ($departmentQuery) => $departmentQuery
                                    ->where('name', 'like', $searchTerm)
                            );
                    });
                }
            )
            ->when(
                $status === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                $status === 'inactive',
                fn ($query) => $query->where('is_active', false)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.function-units.index',
            compact('functionUnits')
        );
    }

    /**
     * Show the form for creating a new Function Unit.
     *
     * Only Super Admin can create Function Units.
     */
    public function create()
    {
        $this->ensureSuperAdmin();

        app(CpcMasterDataSyncService::class)->syncDepartments();

        $departments = Department::where('is_active', true)
            ->whereNotNull('source_function_id')
            ->orderBy('name')
            ->get();

        $cpcSubFunctions = DB::table('cpc_master_sub_functions')
            ->whereNotNull('sub_function_name')
            ->whereNotNull('source_sub_function_id')
            ->get([
                'sub_function_name',
                'source_sub_function_id',
            ]);

        return view(
            'admin.function-units.create',
            compact('departments', 'cpcSubFunctions')
        );
    }

    /**
     * Store a newly created Function Unit.
     *
     * Only Super Admin can create Function Units.
     */
    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                'unique:function_units,code',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $name = strtolower(trim($validated['name']));
        $code = trim((string) ($validated['code'] ?? ''));

        $duplicateExists = DB::table('cpc_master_sub_functions')
            ->where(function ($query) use ($name, $code) {
                $query->whereRaw(
                    'LOWER(TRIM(sub_function_name)) = ?',
                    [$name]
                );

                if ($code !== '') {
                    $query->orWhere(
                        'source_sub_function_id',
                        $code
                    );
                }
            })
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This Function Unit already exists in CPC master data.',
                ]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        FunctionUnit::create($validated);

        return redirect()
            ->route('admin.function-units.index')
            ->with(
                'success',
                'Function Unit created successfully.'
            );
    }

    /**
     * Display the specified Function Unit.
     *
     * All authenticated roles can view Function Unit details.
     */
    public function show(FunctionUnit $functionUnit)
    {
        $functionUnit->load([
            'department',
            'employees',
            'items',
        ]);

        return view(
            'admin.function-units.show',
            compact('functionUnit')
        );
    }

    /**
     * Show the form for editing the specified Function Unit.
     *
     * Only Super Admin can edit Function Units.
     */
    public function edit(FunctionUnit $functionUnit)
    {
        $this->ensureSuperAdmin();

        app(CpcMasterDataSyncService::class)->syncDepartments();

        $departments = Department::where('is_active', true)
            ->whereNotNull('source_function_id')
            ->orderBy('name')
            ->get();

        return view(
            'admin.function-units.edit',
            compact(
                'functionUnit',
                'departments'
            )
        );
    }

    /**
     * Update the specified Function Unit.
     *
     * Only Super Admin can update Function Units.
     */
    public function update(
        Request $request,
        FunctionUnit $functionUnit
    ) {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                'unique:function_units,code,'.$functionUnit->id,
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $functionUnit->update($validated);

        return redirect()
            ->route('admin.function-units.index')
            ->with(
                'success',
                'Function Unit updated successfully.'
            );
    }

    /**
     * Deactivate the specified Function Unit.
     *
     * Only Super Admin can deactivate Function Units.
     */
    public function destroy(FunctionUnit $functionUnit)
    {
        $this->ensureSuperAdmin();

        $functionUnit->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.function-units.index')
            ->with(
                'success',
                'Function Unit deactivated successfully.'
            );
    }

    /**
     * Ensure that the current user is Super Admin.
     *
     * This is the actual server-side security protection.
     */
    private function ensureSuperAdmin(): void
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'super_admin') {
            abort(
                403,
                'Only Super Admin can manage function units.'
            );
        }
    }
}
