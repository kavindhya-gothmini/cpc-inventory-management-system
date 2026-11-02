<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     *
     * All authenticated roles can view departments.
     */
    public function index()
    {
        $departments = Department::with('branch')
            ->latest()
            ->paginate(10);

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     *
     * Super Admin only.
     */
    public function create()
    {
        $this->ensureSuperAdmin();

        $branches = Branch::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.departments.create', compact('branches'));
    }

    /**
     * Store a newly created department.
     *
     * Super Admin only.
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
                'unique:departments,code',
            ],

            'branch_id' => [
                'required',
                'exists:branches,id',
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

        Department::create($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     *
     * All authenticated roles can view a department.
     */
    public function show(Department $department)
    {
        $department->load([
            'branch',
            'functionUnits',
            'employees',
            'items',
        ]);

        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     *
     * Super Admin only.
     */
    public function edit(Department $department)
    {
        $this->ensureSuperAdmin();

        $branches = Branch::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.departments.edit', compact(
            'department',
            'branches'
        ));
    }

    /**
     * Update the specified department.
     *
     * Super Admin only.
     */
    public function update(Request $request, Department $department)
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
                'unique:departments,code,' . $department->id,
            ],

            'branch_id' => [
                'required',
                'exists:branches,id',
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

        $department->update($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Deactivate the specified department.
     *
     * Super Admin only.
     */
    public function destroy(Department $department)
    {
        $this->ensureSuperAdmin();

        $department->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deactivated successfully.');
    }

    /**
     * Ensure the current user is a Super Admin.
     *
     * Only Super Admin can manage department master data.
     */
    private function ensureSuperAdmin(): void
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403, 'Only Super Admin can manage departments.');
        }
    }
}