<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\FunctionUnit;
use Illuminate\Http\Request;

class FunctionUnitController extends Controller
{
    /**
     * Display a listing of Function Units.
     *
     * All authenticated roles can view Function Units.
     */
    public function index()
    {
        $functionUnits = FunctionUnit::with('department')
            ->latest()
            ->paginate(10);

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

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.function-units.create',
            compact('departments')
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

        $departments = Department::where('is_active', true)
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
                'unique:function_units,code,' . $functionUnit->id,
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

        if (!$user || $user->role !== 'super_admin') {
            abort(
                403,
                'Only Super Admin can manage function units.'
            );
        }
    }
}