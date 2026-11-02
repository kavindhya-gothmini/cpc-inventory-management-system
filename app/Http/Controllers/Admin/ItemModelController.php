<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemModel;
use App\Models\Make;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemModelController extends Controller
{
    /**
     * Display a listing of all item models.
     */
    public function index(Request $request): View
    {
        $query = ItemModel::with('make');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('make', function ($makeQuery) use ($search) {
                        $makeQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            }

            if ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Make Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('make_id')) {
            $query->where('make_id', $request->input('make_id'));
        }

        $itemModels = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $makes = Make::orderBy('name')->get();

        return view('admin.item-models.index', compact(
            'itemModels',
            'makes'
        ));
    }


    /**
     * Show the form for creating a new item model.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Only active Makes can be assigned to a new Model.
        |--------------------------------------------------------------------------
        */

        $makes = Make::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.item-models.create', compact('makes'));
    }


    /**
     * Store a newly created item model.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'make_id' => [
                'required',
                'integer',
                Rule::exists('makes', 'id')
                    ->where(fn ($query) => $query->where('is_active', true)),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('item_models', 'name')
                    ->where(fn ($query) => $query->where(
                        'make_id',
                        $request->input('make_id')
                    )),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'make_id.required' => 'Please select a Make.',
            'make_id.exists' => 'The selected Make is invalid or inactive.',

            'name.required' => 'Please enter the Model name.',
            'name.unique' => 'This Model already exists under the selected Make.',

            'description.max' => 'The description may not exceed 1000 characters.',

            'is_active.required' => 'Please select the Model status.',
        ]);

        ItemModel::create($validated);

        return redirect()
            ->route('admin.item-models.index')
            ->with('success', 'Model added successfully.');
    }


    /**
     * Display the specified item model.
     */
    public function show(ItemModel $itemModel): View
    {
        $itemModel->load('make');

        return view('admin.item-models.show', compact('itemModel'));
    }


    /**
     * Show the form for editing the specified item model.
     */
    public function edit(ItemModel $itemModel): View
    {
        /*
        |--------------------------------------------------------------------------
        | Include the current Make even if it has become inactive.
        |--------------------------------------------------------------------------
        |
        | This prevents the existing relationship from disappearing from
        | the edit form.
        |--------------------------------------------------------------------------
        */

        $makes = Make::where(function ($query) use ($itemModel) {
            $query->where('is_active', true)
                ->orWhere('id', $itemModel->make_id);
        })
            ->orderBy('name')
            ->get();

        return view('admin.item-models.edit', compact(
            'itemModel',
            'makes'
        ));
    }


    /**
     * Update the specified item model.
     */
    public function update(
        Request $request,
        ItemModel $itemModel
    ): RedirectResponse {
        $validated = $request->validate([
            'make_id' => [
                'required',
                'integer',
                Rule::exists('makes', 'id')
                    ->where(function ($query) use ($itemModel) {
                        $query->where('is_active', true)
                            ->orWhere('id', $itemModel->make_id);
                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:150',

                Rule::unique('item_models', 'name')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'make_id',
                            $request->input('make_id')
                        );
                    })
                    ->ignore($itemModel->id),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'make_id.required' => 'Please select a Make.',
            'make_id.exists' => 'The selected Make is invalid or inactive.',

            'name.required' => 'Please enter the Model name.',
            'name.unique' => 'This Model already exists under the selected Make.',

            'description.max' => 'The description may not exceed 1000 characters.',

            'is_active.required' => 'Please select the Model status.',
        ]);

        $itemModel->update($validated);

        return redirect()
            ->route('admin.item-models.index')
            ->with('success', 'Model updated successfully.');
    }


    /**
     * Remove the specified item model.
     */
    public function destroy(ItemModel $itemModel): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deletion if the Model is already being used by Items.
        |--------------------------------------------------------------------------
        */

        $itemsCount = $itemModel->items()->count();

        if ($itemsCount > 0) {
            return redirect()
                ->route('admin.item-models.index')
                ->with(
                    'error',
                    "This Model cannot be deleted because it is currently assigned to {$itemsCount} inventory item(s)."
                );
        }

        $itemModel->delete();

        return redirect()
            ->route('admin.item-models.index')
            ->with('success', 'Model deleted successfully.');
    }
}