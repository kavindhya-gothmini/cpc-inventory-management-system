<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePartSupplier;
use App\Models\SupplierDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SparePartSupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request): View
    {
        $query = SparePartSupplier::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'supplier_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'supplier_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'contact_person',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'alternative_phone',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'city',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Active / Inactive Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if (
                $request->input('status') === 'active'
            ) {

                $query->where(
                    'is_active',
                    true
                );
            }

            if (
                $request->input('status') === 'inactive'
            ) {

                $query->where(
                    'is_active',
                    false
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Supplier Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('supplier_type')) {

            $query->where(
                'supplier_type',
                $request->input('supplier_type')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Suppliers
        |--------------------------------------------------------------------------
        */

        $suppliers = $query
            ->withCount('documents')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Supplier Types
        |--------------------------------------------------------------------------
        */

        $supplierTypes = SparePartSupplier::query()
            ->whereNotNull('supplier_type')
            ->where(
                'supplier_type',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('supplier_type')
            ->pluck('supplier_type');


        return view(
            'admin.inventory.repairs.suppliers.index',
            compact(
                'suppliers',
                'supplierTypes'
            )
        );
    }


    /**
     * Show supplier creation form.
     */
    public function create(): View
    {
        return view(
            'admin.inventory.repairs.suppliers.create'
        );
    }


    /**
     * Store a new supplier.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Supplier Information
            |--------------------------------------------------------------------------
            */

            'supplier_code' => [
                'required',
                'string',
                'max:50',
                'unique:spare_part_suppliers,supplier_code',
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:150',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'alternative_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'supplier_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],


            /*
            |--------------------------------------------------------------------------
            | Supplier Document
            |--------------------------------------------------------------------------
            */

            'document' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],

            'document_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_description' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Active Status
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] =
            $request->boolean(
                'is_active',
                true
            );


        /*
        |--------------------------------------------------------------------------
        | Create Supplier
        |--------------------------------------------------------------------------
        */

        $supplier = SparePartSupplier::create([

            'supplier_code' =>
                $validated['supplier_code'],

            'supplier_name' =>
                $validated['supplier_name'],

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'alternative_phone' =>
                $validated['alternative_phone']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'address' =>
                $validated['address']
                ?? null,

            'city' =>
                $validated['city']
                ?? null,

            'supplier_type' =>
                $validated['supplier_type']
                ?? null,

            'notes' =>
                $validated['notes']
                ?? null,

            'is_active' =>
                $validated['is_active'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Supplier Document
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('document')) {

            $this->storeSupplierDocument(
                $request,
                $supplier
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.inventory.repairs.suppliers.show',
                $supplier
            )
            ->with(
                'success',
                'Supplier created successfully.'
            );
    }


    /**
     * Display supplier details.
     */
    public function show(
        SparePartSupplier $supplier
    ): View {

        $supplier->load('documents');

        $documentsCount =
            $supplier->documents->count();

        return view(
            'admin.inventory.repairs.suppliers.show',
            compact(
                'supplier',
                'documentsCount'
            )
        );
    }


    /**
     * Show supplier edit form.
     */
    public function edit(
        SparePartSupplier $supplier
    ): View {

        $supplier->load('documents');

        return view(
            'admin.inventory.repairs.suppliers.edit',
            compact('supplier')
        );
    }


    /**
     * Update supplier.
     *
     * Also allows adding a new supplier document.
     */
    public function update(
        Request $request,
        SparePartSupplier $supplier
    ): RedirectResponse {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Supplier Information
            |--------------------------------------------------------------------------
            */

            'supplier_code' => [

                'required',

                'string',

                'max:50',

                Rule::unique(
                    'spare_part_suppliers',
                    'supplier_code'
                )->ignore(
                    $supplier->id
                ),
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:150',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'alternative_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'supplier_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],


            /*
            |--------------------------------------------------------------------------
            | New Supplier Document
            |--------------------------------------------------------------------------
            */

            'document' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],

            'document_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_description' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Checkbox Handling
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] =
            $request->boolean(
                'is_active',
                false
            );


        /*
        |--------------------------------------------------------------------------
        | Update Supplier
        |--------------------------------------------------------------------------
        */

        $supplier->update([

            'supplier_code' =>
                $validated['supplier_code'],

            'supplier_name' =>
                $validated['supplier_name'],

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'alternative_phone' =>
                $validated['alternative_phone']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'address' =>
                $validated['address']
                ?? null,

            'city' =>
                $validated['city']
                ?? null,

            'supplier_type' =>
                $validated['supplier_type']
                ?? null,

            'notes' =>
                $validated['notes']
                ?? null,

            'is_active' =>
                $validated['is_active'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Add New Supplier Document
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('document')) {

            $this->storeSupplierDocument(
                $request,
                $supplier
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.inventory.repairs.suppliers.show',
                $supplier
            )
            ->with(
                'success',
                'Supplier updated successfully.'
            );
    }


    /**
     * Delete supplier.
     *
     * Deletes all attached supplier document files
     * before deleting the supplier.
     */
    public function destroy(
        SparePartSupplier $supplier
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Load Documents
        |--------------------------------------------------------------------------
        */

        $supplier->load('documents');


        /*
        |--------------------------------------------------------------------------
        | Delete Physical Files
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplier->documents
            as $document
        ) {

            if (
                $document->file_path &&
                Storage::disk('public')->exists(
                    $document->file_path
                )
            ) {

                Storage::disk('public')->delete(
                    $document->file_path
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Supplier
        |--------------------------------------------------------------------------
        |
        | supplier_documents records are automatically
        | deleted because the foreign key uses
        | cascadeOnDelete().
        |
        */

        $supplier->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.inventory.repairs.suppliers.index'
            )
            ->with(
                'success',
                'Supplier deleted successfully.'
            );
    }


    /**
     * Activate supplier.
     */
    public function activate(
        SparePartSupplier $supplier
    ): RedirectResponse {

        $supplier->update([
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Supplier activated successfully.'
            );
    }


    /**
     * Deactivate supplier.
     */
    public function deactivate(
        SparePartSupplier $supplier
    ): RedirectResponse {

        $supplier->update([
            'is_active' => false,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Supplier deactivated successfully.'
            );
    }


    /**
     * Update an individual supplier document.
     *
     * This updates ONLY the document information.
     * The supplier and physical file remain unchanged.
     */
    public function updateDocument(
        Request $request,
        SparePartSupplier $supplier,
        SupplierDocument $document
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | Make sure the selected document actually belongs
        | to the selected supplier.
        |
        */

        if (
            (int) $document->spare_part_supplier_id
            !== (int) $supplier->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Document Information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'document_name' => [
                'required',
                'string',
                'max:150',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Document
        |--------------------------------------------------------------------------
        |
        | Only metadata is updated.
        | The physical uploaded file is NOT replaced.
        |
        */

        $document->update([

            'document_name' =>
                $validated['document_name'],

            'document_type' =>
                $validated['document_type']
                ?? null,

            'description' =>
                $validated['description']
                ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.inventory.repairs.suppliers.edit',
                $supplier
            )
            ->with(
                'success',
                'Supplier document updated successfully.'
            );
    }


    /**
     * Delete an individual supplier document.
     *
     * IMPORTANT:
     * This deletes ONLY the selected document.
     * The supplier remains unchanged.
     */
    public function destroyDocument(
        SparePartSupplier $supplier,
        SupplierDocument $document
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | Make sure the document actually belongs
        | to the selected supplier.
        |
        */

        if (
            $document->spare_part_supplier_id
            !== $supplier->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Physical File
        |--------------------------------------------------------------------------
        */

        if (
            $document->file_path &&
            Storage::disk('public')->exists(
                $document->file_path
            )
        ) {

            Storage::disk('public')->delete(
                $document->file_path
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $document->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.inventory.repairs.suppliers.show',
                $supplier
            )
            ->with(
                'success',
                'Supplier document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPER
    |--------------------------------------------------------------------------
    |
    | Store supplier document in:
    |
    | storage/app/public/supplier-documents/
    |
    */

    private function storeSupplierDocument(
        Request $request,
        SparePartSupplier $supplier
    ): SupplierDocument {

        $file = $request->file('document');


        /*
        |--------------------------------------------------------------------------
        | Store File
        |--------------------------------------------------------------------------
        */

        $path = $file->store(
            'supplier-documents',
            'public'
        );


        /*
        |--------------------------------------------------------------------------
        | Document Name
        |--------------------------------------------------------------------------
        |
        | If the user does not enter a document name,
        | use the original filename without extension.
        |
        */

        $documentName =
            $request->input('document_name');

        if (
            !$documentName ||
            trim($documentName) === ''
        ) {

            $documentName = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Database Record
        |--------------------------------------------------------------------------
        */

        return SupplierDocument::create([

            'spare_part_supplier_id' =>
                $supplier->id,

            'document_name' =>
                $documentName,

            'document_type' =>
                $request->input(
                    'document_type'
                ),

            'file_path' =>
                $path,

            'original_filename' =>
                $file->getClientOriginalName(),

            'mime_type' =>
                $file->getClientMimeType(),

            'file_size' =>
                $file->getSize(),

            'description' =>
                $request->input(
                    'document_description'
                ),
        ]);
    }
}