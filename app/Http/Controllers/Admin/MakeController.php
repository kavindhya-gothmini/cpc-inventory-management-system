<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemModel;
use App\Models\Make;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MakeController extends Controller
{
    /**
     * Display a listing of Makes.
     */
    public function index(): View
    {
        /*
         * Load Makes with the number of associated Item Models.
         *
         * Pagination is kept at 10 records per page.
         */
        $makes = Make::withCount('itemModels')
            ->orderBy('name')
            ->paginate(10);

        /*
         * Global statistics.
         *
         * These are intentionally calculated separately from the
         * paginated $makes collection so the dashboard statistics
         * represent the entire Make master table, not just the
         * current page.
         */
        $totalMakes = Make::count();

        $activeMakes = Make::where('is_active', true)
            ->count();

        $inactiveMakes = Make::where('is_active', false)
            ->count();

        $totalModels = ItemModel::count();

        return view('admin.makes.index', compact(
            'makes',
            'totalMakes',
            'activeMakes',
            'inactiveMakes',
            'totalModels'
        ));
    }


    /**
     * Show the form for creating a new Make.
     */
    public function create(): View
    {
        return view('admin.makes.create');
    }


    /**
     * Store a newly created Make.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:makes,name',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        Make::create($validated);

        return redirect()
            ->route('admin.makes.index')
            ->with('success', 'Make added successfully.');
    }


    /**
     * Show the form for editing the specified Make.
     */
    public function edit(Make $make): View
    {
        return view('admin.makes.edit', compact('make'));
    }


    /**
     * Update the specified Make.
     */
    public function update(
        Request $request,
        Make $make
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:makes,name,' . $make->id,
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $make->update($validated);

        return redirect()
            ->route('admin.makes.index')
            ->with('success', 'Make updated successfully.');
    }


    /**
     * Remove the specified Make.
     */
    public function destroy(Make $make): RedirectResponse
    {
        /*
         * Do not allow a Make to be deleted when Item Models
         * are still associated with it.
         */
        if ($make->itemModels()->exists()) {

            return redirect()
                ->route('admin.makes.index')
                ->with(
                    'error',
                    'This Make cannot be deleted because models are associated with it. ' .
                    'Deactivate the Make instead or remove its models first.'
                );
        }

        $make->delete();

        return redirect()
            ->route('admin.makes.index')
            ->with('success', 'Make deleted successfully.');
    }
}

