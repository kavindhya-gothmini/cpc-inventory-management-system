<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    /**
     * Display a listing of item types.
     */
    public function index()
    {
        $itemTypes = ItemType::latest()->paginate(10);

        return view('admin.item-types.index', compact('itemTypes'));
    }

    /**
     * Show the form for creating a new item type.
     */
    public function create()
    {
        return view('admin.item-types.create');
    }

    /**
     * Store a newly created item type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        ItemType::create($validated);

        return redirect()
            ->route('admin.item-types.index')
            ->with('success', 'Item type created successfully.');
    }

    /**
     * Display the specified item type.
     */
    public function show(ItemType $itemType)
    {
        $itemType->load('items');

        return view('admin.item-types.show', compact('itemType'));
    }

    /**
     * Show the form for editing the specified item type.
     */
    public function edit(ItemType $itemType)
    {
        return view('admin.item-types.edit', compact('itemType'));
    }

    /**
     * Update the specified item type.
     */
    public function update(Request $request, ItemType $itemType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $itemType->update($validated);

        return redirect()
            ->route('admin.item-types.index')
            ->with('success', 'Item type updated successfully.');
    }

    /**
     * Remove the specified item type.
     *
     * We do NOT delete the record because item types may already
     * be connected to inventory items. Instead, deactivate it.
     */
    public function destroy(ItemType $itemType)
    {
        $itemType->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.item-types.index')
            ->with('success', 'Item type deactivated successfully.');
    }
}