<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\TemporaryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemporaryItemController extends Controller
{
    /**
     * Display a listing of temporary items.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $items = TemporaryItem::query()
            ->withCount('issues')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('item_code', 'like', "%{$search}%")
                        ->orWhere('item_type', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.inventory.temporary-items.index',
            compact('items', 'search')
        );
    }

    /**
     * Show the form for creating a new temporary item.
     */
    public function create(): View
    {
        return view('admin.inventory.temporary-items.create');
    }

    /**
     * Store a newly created temporary item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:100',
                'unique:temporary_items,item_code',
            ],

            'item_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:available,issued,inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        TemporaryItem::create($validated);

        return redirect()
            ->route('admin.inventory.temporary-items.index')
            ->with(
                'success',
                'Temporary item added successfully.'
            );
    }

    /**
     * Display the specified temporary item.
     */
    public function show(TemporaryItem $temporaryItem): View
    {
        $temporaryItem->load([
            'creator',
            'updater',
        ]);

        $issueCount = $temporaryItem
            ->issues()
            ->count();

        return view(
            'admin.inventory.temporary-items.show',
            compact(
                'temporaryItem',
                'issueCount'
            )
        );
    }

    /**
     * Show the form for editing the specified temporary item.
     */
    public function edit(TemporaryItem $temporaryItem): View
    {
        return view(
            'admin.inventory.temporary-items.edit',
            compact('temporaryItem')
        );
    }

    /**
     * Update the specified temporary item.
     */
    public function update(
        Request $request,
        TemporaryItem $temporaryItem
    ): RedirectResponse {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:100',
                'unique:temporary_items,item_code,' . $temporaryItem->id,
            ],

            'item_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:available,issued,inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $validated['updated_by'] = auth()->id();

        $temporaryItem->update($validated);

        return redirect()
            ->route('admin.inventory.temporary-items.index')
            ->with(
                'success',
                'Temporary item updated successfully.'
            );
    }

    /**
     * Remove the specified temporary item.
     */
    public function destroy(
        TemporaryItem $temporaryItem
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Preserve Issue History
        |--------------------------------------------------------------------------
        |
        | If this temporary item has already been used in an issue,
        | do not permanently delete it. Instead, mark it inactive
        | so the historical issue records remain valid.
        |
        |--------------------------------------------------------------------------
        */

        if ($temporaryItem->issues()->exists()) {
            $temporaryItem->update([
                'status' => 'inactive',
                'updated_by' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.inventory.temporary-items.index')
                ->with(
                    'success',
                    'Temporary item has been deactivated because it has existing issue history.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Permanent Delete
        |--------------------------------------------------------------------------
        |
        | If the item has never been issued, it can be permanently
        | removed from the temporary item register.
        |
        |--------------------------------------------------------------------------
        */

        $temporaryItem->delete();

        return redirect()
            ->route('admin.inventory.temporary-items.index')
            ->with(
                'success',
                'Temporary item deleted successfully.'
            );
    }
}

