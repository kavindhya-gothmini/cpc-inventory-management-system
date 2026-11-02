<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of branches.
     *
     * All authenticated roles can view branches.
     */
    public function index()
    {
        $branches = Branch::latest()->paginate(10);

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     *
     * Super Admin only.
     */
    public function create()
    {
        $this->ensureSuperAdmin();

        return view('admin.branches.create');
    }

    /**
     * Store a newly created branch.
     *
     * Super Admin only.
     */
    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            // Branch code must be exactly 4 numeric digits
            // and must be unique.
            'code' => [
                'required',
                'digits:4',
                'unique:branches,code',
            ],

            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Branch::create($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified branch.
     *
     * All authenticated roles can view a branch.
     */
    public function show(Branch $branch)
    {
        $branch->load([
            'departments',
            'employees',
            'items',
        ]);

        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified branch.
     *
     * Super Admin only.
     */
    public function edit(Branch $branch)
    {
        $this->ensureSuperAdmin();

        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch.
     *
     * Super Admin only.
     */
    public function update(Request $request, Branch $branch)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            // Branch code must be exactly 4 numeric digits
            // and must be unique except for the current branch.
            'code' => [
                'required',
                'digits:4',
                'unique:branches,code,' . $branch->id,
            ],

            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $branch->update($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Deactivate the specified branch.
     *
     * Super Admin only.
     */
    public function destroy(Branch $branch)
    {
        $this->ensureSuperAdmin();

        $branch->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch deactivated successfully.');
    }

    /**
     * Ensure the current user is a Super Admin.
     *
     * Only Super Admin can manage branch master data.
     */
    private function ensureSuperAdmin(): void
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403, 'Only Super Admin can manage branches.');
        }
    }
}