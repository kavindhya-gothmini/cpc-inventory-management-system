<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\TemporaryItemIssue;
use App\Models\TemporaryItemIssueHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TemporaryItemIssueController extends Controller
{
    /**
     * Item types currently supported by the system.
     */
    private array $itemTypes = [
        'PC',
        'Projector',
        'Laptop',
    ];

    /**
     * Display all temporary item issues.
     */
    public function index(Request $request): View
    {
        $query = TemporaryItemIssue::with([
            'department',
            'functionUnit',
            'requestingEmployee',
            'collectingEmployee',
            'returningEmployee',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('item_type', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")

                    ->orWhereHas(
                        'requestingEmployee',
                        function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere(
                                    'epf_no',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    )

                    ->orWhereHas(
                        'collectingEmployee',
                        function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere(
                                    'epf_no',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    )

                    ->orWhereHas(
                        'returningEmployee',
                        function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere(
                                    'epf_no',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $temporaryIssues = $query
            ->latest('issue_datetime')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.temporary-item-issues.index',
            compact('temporaryIssues')
        );
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $functionUnits = FunctionUnit::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        $itemTypes = $this->itemTypes;

        return view(
            'admin.temporary-item-issues.create',
            compact(
                'departments',
                'functionUnits',
                'employees',
                'itemTypes'
            )
        );
    }

    /**
     * Store a new temporary item issue.
     *
     * Workflow:
     *
     * CREATE → ISSUED
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'issue_datetime' => [
                'required',
                'date',
            ],

            'item_code' => [
                'required',
                'string',
                'max:255',
            ],

            'item_type' => [
                'required',
                'string',
                'in:' . implode(',', $this->itemTypes),
            ],

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'function_unit_id' => [
                'nullable',
                'exists:function_units,id',
            ],

            'event' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Requesting Employee
            |--------------------------------------------------------------------------
            */

            'requesting_employee_id' => [
                'required',
                'exists:employees,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Collecting Employee
            |--------------------------------------------------------------------------
            |
            | This is the employee who physically collects the temporary
            | item from the IT Function.
            |
            */

            'collecting_employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Create Issue
        |--------------------------------------------------------------------------
        |
        | A newly created temporary item issue is immediately ISSUED.
        |
        */

        $temporaryIssue = TemporaryItemIssue::create([
            ...$validated,

            'status' => 'issued',

            'created_by' => $user?->id,
            'updated_by' => $user?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create History
        |--------------------------------------------------------------------------
        */

        $this->createHistory(
            $temporaryIssue,
            'created',
            'Temporary item issue record was created and marked as issued.'
        );

        return redirect()
            ->route('admin.temporary-item-issues.index')
            ->with(
                'success',
                'Temporary item issue created successfully and marked as issued.'
            );
    }

    /**
     * Display a single temporary item issue.
     */
    public function show(
        TemporaryItemIssue $temporaryItemIssue
    ): View {
        $temporaryItemIssue->load([
            'department',
            'functionUnit',
            'requestingEmployee',
            'collectingEmployee',
            'returningEmployee',
            'creator',
            'updater',
            'histories.changedBy',
        ]);

        return view(
            'admin.temporary-item-issues.show',
            compact('temporaryItemIssue')
        );
    }

    /**
     * Show the edit form.
     */
    public function edit(
        TemporaryItemIssue $temporaryItemIssue
    ): View {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $functionUnits = FunctionUnit::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        $itemTypes = $this->itemTypes;

        return view(
            'admin.temporary-item-issues.edit',
            compact(
                'temporaryItemIssue',
                'departments',
                'functionUnits',
                'employees',
                'itemTypes'
            )
        );
    }

    /**
     * Update a temporary item issue.
     *
     * Employee information:
     *
     * - Requesting employee
     * - Collecting employee
     *
     * The returning employee is recorded when the item is received.
     */
    public function update(
        Request $request,
        TemporaryItemIssue $temporaryItemIssue
    ): RedirectResponse {
        $validated = $request->validate([
            'issue_datetime' => [
                'required',
                'date',
            ],

            'item_code' => [
                'required',
                'string',
                'max:255',
            ],

            'item_type' => [
                'required',
                'string',
                'in:' . implode(',', $this->itemTypes),
            ],

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'function_unit_id' => [
                'nullable',
                'exists:function_units,id',
            ],

            'event' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Requesting Employee
            |--------------------------------------------------------------------------
            */

            'requesting_employee_id' => [
                'required',
                'exists:employees,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Collecting Employee
            |--------------------------------------------------------------------------
            */

            'collecting_employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values For History
        |--------------------------------------------------------------------------
        */

        $oldValues = $temporaryItemIssue->only([
            'issue_datetime',
            'item_code',
            'item_type',
            'department_id',
            'function_unit_id',
            'event',
            'requesting_employee_id',
            'collecting_employee_id',
            'remarks',
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Update Issue
        |--------------------------------------------------------------------------
        */

        $temporaryItemIssue->update([
            ...$validated,

            'updated_by' => $user?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create History If Something Changed
        |--------------------------------------------------------------------------
        */

        $changedFields = [];

        foreach ($oldValues as $field => $oldValue) {
            $newValue = $temporaryItemIssue->{$field};

            if ((string) $oldValue !== (string) $newValue) {
                $changedFields[] = $field;
            }
        }

        if (!empty($changedFields)) {
            $this->createHistory(
                $temporaryItemIssue,
                'updated',
                'Updated fields: ' . implode(', ', $changedFields)
            );
        }

        return redirect()
            ->route(
                'admin.temporary-item-issues.show',
                $temporaryItemIssue
            )
            ->with(
                'success',
                'Temporary item issue updated successfully.'
            );
    }

    /**
     * Receive the temporary item back into the IT Function.
     *
     * Workflow:
     *
     * ISSUED → RECEIVED
     *
     * The returning employee is recorded here.
     */
    public function receive(
        Request $request,
        TemporaryItemIssue $temporaryItemIssue
    ): RedirectResponse {
        $validated = $request->validate([
            'received_datetime' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Returning Employee
            |--------------------------------------------------------------------------
            |
            | This is the employee who physically returns the temporary
            | item to the IT Function.
            |
            */

            'returning_employee_id' => [
                'required',
                'exists:employees,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Current Status
        |--------------------------------------------------------------------------
        */

        if ($temporaryItemIssue->status === 'cancelled') {
            return back()->with(
                'error',
                'A cancelled temporary item issue cannot be received.'
            );
        }

        if ($temporaryItemIssue->status === 'received') {
            return back()->with(
                'error',
                'This temporary item has already been received.'
            );
        }

        if ($temporaryItemIssue->status !== 'issued') {
            return back()->with(
                'error',
                'This temporary item is not available for receiving.'
            );
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Update Issue
        |--------------------------------------------------------------------------
        */

        $temporaryItemIssue->update([
            'received_datetime' => $validated['received_datetime'],

            'returning_employee_id' =>
                $validated['returning_employee_id'],

            'status' => 'received',

            'updated_by' => $user?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Returning Employee
        |--------------------------------------------------------------------------
        */

        $employee = Employee::find(
            $validated['returning_employee_id']
        );

        $employeeDescription = $employee
            ? "Item returned by {$employee->name} (EPF: {$employee->epf_no}) and received by the IT Function."
            : 'Temporary item was returned and received by the IT Function.';

        /*
        |--------------------------------------------------------------------------
        | Create History
        |--------------------------------------------------------------------------
        */

        $this->createHistory(
            $temporaryItemIssue,
            'received',
            $employeeDescription
        );

        return redirect()
            ->route(
                'admin.temporary-item-issues.show',
                $temporaryItemIssue
            )
            ->with(
                'success',
                'Temporary item received successfully.'
            );
    }

    /**
     * Cancel a temporary item issue.
     *
     * Workflow:
     *
     * ISSUED → CANCELLED
     */
    public function cancel(
        TemporaryItemIssue $temporaryItemIssue
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Validate Current Status
        |--------------------------------------------------------------------------
        */

        if ($temporaryItemIssue->status === 'received') {
            return back()->with(
                'error',
                'A received temporary item cannot be cancelled.'
            );
        }

        if ($temporaryItemIssue->status === 'cancelled') {
            return back()->with(
                'error',
                'This temporary item issue is already cancelled.'
            );
        }

        if ($temporaryItemIssue->status !== 'issued') {
            return back()->with(
                'error',
                'Only an issued temporary item can be cancelled.'
            );
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Update Status
        |--------------------------------------------------------------------------
        */

        $temporaryItemIssue->update([
            'status' => 'cancelled',

            'updated_by' => $user?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create History
        |--------------------------------------------------------------------------
        */

        $this->createHistory(
            $temporaryItemIssue,
            'cancelled',
            'Temporary item issue was cancelled.'
        );

        return back()->with(
            'success',
            'Temporary item issue cancelled successfully.'
        );
    }

    /**
     * Delete a temporary item issue.
     *
     * History records are automatically removed because the
     * history migration uses cascadeOnDelete().
     */
    public function destroy(
        TemporaryItemIssue $temporaryItemIssue
    ): RedirectResponse {
        $temporaryItemIssue->delete();

        return redirect()
            ->route('admin.temporary-item-issues.index')
            ->with(
                'success',
                'Temporary item issue deleted successfully.'
            );
    }

    /**
     * Create an audit-history entry.
     */
    private function createHistory(
        TemporaryItemIssue $temporaryIssue,
        string $action,
        string $description
    ): void {
        $user = Auth::user();

        TemporaryItemIssueHistory::create([
            'temporary_item_issue_id' => $temporaryIssue->id,

            'action' => $action,

            'description' => $description,

            'changed_by' => $user?->id,

            'changed_by_name' => $user?->name,

            'changed_by_epf' => $user?->epf_number,

            'changed_by_role' => $user?->role,

            'changed_at' => now(),
        ]);
    }
}

