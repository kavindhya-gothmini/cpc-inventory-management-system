<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SparePartController extends Controller
{
    /**
     * Display spare parts.
     */
    public function index(Request $request): View
    {
        $query = SparePart::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('part_code', 'like', "%{$search}%")
                    ->orWhere('part_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('supplier_name', 'like', "%{$search}%")
                    ->orWhere('supplier_reference', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'low') {

                $query->whereColumn(
                    'quantity',
                    '<=',
                    'minimum_stock'
                );
            }

            if ($request->stock_status === 'out') {

                $query->where(
                    'quantity',
                    0
                );
            }

            if ($request->stock_status === 'available') {

                $query->where(
                    'quantity',
                    '>',
                    0
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Warranty Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('warranty_status')) {

            if ($request->warranty_status === 'active') {

                $query->where(function ($q) {

                    $q->whereNull('warranty_end')
                        ->orWhereDate(
                            'warranty_end',
                            '>=',
                            now()->toDateString()
                        );

                });
            }

            if ($request->warranty_status === 'expired') {

                $query->whereNotNull('warranty_end')
                    ->whereDate(
                        'warranty_end',
                        '<',
                        now()->toDateString()
                    );
            }

            if ($request->warranty_status === 'expiring') {

                $query->whereNotNull('warranty_end')
                    ->whereBetween(
                        'warranty_end',
                        [
                            now()->toDateString(),
                            now()->addDays(30)->toDateString(),
                        ]
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        $categories = SparePart::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalParts = SparePart::count();

        $activeParts = SparePart::where(
            'status',
            'active'
        )->count();

        $inactiveParts = SparePart::where(
            'status',
            'inactive'
        )->count();

        $lowStockParts = SparePart::whereColumn(
            'quantity',
            '<=',
            'minimum_stock'
        )->count();

        $outOfStockParts = SparePart::where(
            'quantity',
            0
        )->count();

        $totalStock = SparePart::sum('quantity');

        $totalStockValue = SparePart::query()
            ->selectRaw(
                'COALESCE(SUM(quantity * unit_price), 0) as total'
            )
            ->value('total');


        /*
        |--------------------------------------------------------------------------
        | Paginated Spare Parts
        |--------------------------------------------------------------------------
        */
        $spareParts = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.inventory.repairs.spare-parts.index',
            compact(
                'spareParts',
                'categories',
                'totalParts',
                'activeParts',
                'inactiveParts',
                'lowStockParts',
                'outOfStockParts',
                'totalStock',
                'totalStockValue'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(
            'admin.inventory.repairs.spare-parts.create'
        );
    }


    /**
     * Store spare part.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([

            'part_code' => [
                'required',
                'string',
                'max:255',
                'unique:spare_parts,part_code',
            ],

            'part_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'supplier_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'supplier_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'warranty_start' => [
                'nullable',
                'date',
            ],

            'warranty_end' => [
                'nullable',
                'date',
                'after_or_equal:warranty_start',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);


        DB::transaction(function () use ($validated) {

            $quantity = (int) $validated['quantity'];

            /*
            |--------------------------------------------------------------------------
            | Create Spare Part
            |--------------------------------------------------------------------------
            */
            $sparePart = SparePart::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Initial Stock Movement
            |--------------------------------------------------------------------------
            */
            if ($quantity > 0) {

                $sparePart->stockMovements()->create([

                    'movement_type' => 'stock_in',

                    'quantity' => $quantity,

                    'quantity_before' => 0,

                    'quantity_after' => $quantity,

                    'reference' => 'INITIAL-STOCK',

                    'remarks' =>
                        'Initial stock received when spare part was created.',

                ]);
            }
        });


        return redirect()
            ->route('admin.inventory.repairs.spare-parts.index')
            ->with(
                'success',
                'Spare part created successfully.'
            );
    }


    /**
     * Display spare part.
     */
    public function show(SparePart $sparePart): View
    {
        $sparePart->load([
            'stockMovements.repair',
            'documents',
            'repairSpareParts.repair',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Recent Stock Movements
        |--------------------------------------------------------------------------
        */
        $recentMovements = $sparePart
            ->stockMovements()
            ->with('repair')
            ->latest()
            ->limit(20)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Usage
        |--------------------------------------------------------------------------
        */
        $recentUsage = $sparePart
            ->repairSpareParts()
            ->with([
                'repair.item',
            ])
            ->latest()
            ->limit(20)
            ->get();


        return view(
            'admin.inventory.repairs.spare-parts.show',
            compact(
                'sparePart',
                'recentMovements',
                'recentUsage'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(SparePart $sparePart): View
    {
        return view(
            'admin.inventory.repairs.spare-parts.edit',
            compact('sparePart')
        );
    }


    /**
     * Update spare part.
     */
    public function update(
        Request $request,
        SparePart $sparePart
    ): RedirectResponse {

        $validated = $request->validate([

            'part_code' => [
                'required',
                'string',
                'max:255',
                'unique:spare_parts,part_code,' . $sparePart->id,
            ],

            'part_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'supplier_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'supplier_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'warranty_start' => [
                'nullable',
                'date',
            ],

            'warranty_end' => [
                'nullable',
                'date',
                'after_or_equal:warranty_start',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);


        DB::transaction(function () use (
            $validated,
            $sparePart
        ) {

            $oldQuantity = (int) $sparePart->quantity;

            $newQuantity = (int) $validated['quantity'];


            /*
            |--------------------------------------------------------------------------
            | Update Spare Part
            |--------------------------------------------------------------------------
            */
            $sparePart->update(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Record Manual Stock Adjustment
            |--------------------------------------------------------------------------
            */
            if ($oldQuantity !== $newQuantity) {

                $difference = abs(
                    $newQuantity - $oldQuantity
                );


                $sparePart->stockMovements()->create([

                    'movement_type' => 'adjustment',

                    'quantity' => $difference,

                    'quantity_before' => $oldQuantity,

                    'quantity_after' => $newQuantity,

                    'reference' => 'MANUAL-ADJUSTMENT',

                    'remarks' =>
                        'Stock quantity adjusted from '
                        . $oldQuantity
                        . ' to '
                        . $newQuantity
                        . '.',

                ]);
            }
        });


        return redirect()
            ->route(
                'admin.inventory.repairs.spare-parts.show',
                $sparePart
            )
            ->with(
                'success',
                'Spare part updated successfully.'
            );
    }


    /**
     * Delete spare part.
     */
    public function destroy(
        SparePart $sparePart
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Prevent deletion when used in repairs
        |--------------------------------------------------------------------------
        */
        $hasUsage = $sparePart
            ->repairSpareParts()
            ->exists();


        if ($hasUsage) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This spare part cannot be deleted because it has already been used in a repair.'
                );
        }


        DB::transaction(function () use ($sparePart) {

            $sparePart->delete();
        });


        return redirect()
            ->route(
                'admin.inventory.repairs.spare-parts.index'
            )
            ->with(
                'success',
                'Spare part deleted successfully.'
            );
    }
}
