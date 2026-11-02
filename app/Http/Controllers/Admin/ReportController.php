<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AssignmentReportExport;
use App\Exports\InventoryReportExport;
use App\Exports\RepairReportExport;
use App\Exports\RddReportExport;
use App\Exports\TemporaryItemReportExport;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\Item;
use App\Models\ItemAssignment;
use App\Models\ItemType;
use App\Models\Repair;
use App\Models\RddTransaction;
use App\Models\TemporaryItemIssue;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REPORT DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.reports.index');
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT REPORTS
    |--------------------------------------------------------------------------
    */

    public function assignmentReports()
    {
        $reportType = request('report_type', 'current');

        $employeeId = request('employee_id');
        $status = request('status');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $functionUnitId = request('function_unit_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $employees = Employee::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $functionUnits = FunctionUnit::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $assignmentQuery = ItemAssignment::query()
            ->with([
                'item.itemType',
                'employee',
                'branch',
                'department',
                'functionUnit',
            ]);

        $this->applyAssignmentReportFilters(
            $assignmentQuery,
            $reportType,
            $employeeId,
            $status,
            $branchId,
            $departmentId,
            $functionUnitId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        $assignments = collect();

        if ($reportType !== 'unassigned') {
            $assignments = $assignmentQuery
                ->latest('id')
                ->paginate(25)
                ->withQueryString();
        }

        $currentAssignments = ItemAssignment::query()
            ->whereNull('returned_date')
            ->count();

        $returnedAssignments = ItemAssignment::query()
            ->whereNotNull('returned_date')
            ->count();

        $totalAssignments = ItemAssignment::query()->count();

        $unassignedAssets = Item::query()
            ->whereDoesntHave(
                'assignments',
                function ($query) {
                    $query->whereNull('returned_date');
                }
            )
            ->count();

        $employeeAssets = ItemAssignment::query()
            ->whereNull('returned_date')
            ->whereNotNull('employee_id')
            ->distinct('item_id')
            ->count('item_id');

        $unassignedItems = collect();

        if ($reportType === 'unassigned') {
            $unassignedQuery = Item::query()
                ->with([
                    'itemType',
                    'branch',
                    'department',
                    'currentEmployee',
                ])
                ->whereDoesntHave(
                    'assignments',
                    function ($query) {
                        $query->whereNull('returned_date');
                    }
                );

            $this->applyUnassignedReportFilters(
                $unassignedQuery,
                $branchId,
                $departmentId,
                $itemTypeId,
                $fromDate,
                $toDate
            );

            $unassignedItems = $unassignedQuery
                ->latest('id')
                ->paginate(25)
                ->withQueryString();
        }

        $filteredCurrentAssignments = 0;
        $filteredReturnedAssignments = 0;
        $filteredTotalAssignments = 0;

        if ($reportType !== 'unassigned') {
            $filteredBaseQuery = ItemAssignment::query();

            $this->applyAssignmentReportFilters(
                $filteredBaseQuery,
                $reportType,
                $employeeId,
                $status,
                $branchId,
                $departmentId,
                $functionUnitId,
                $itemTypeId,
                $fromDate,
                $toDate
            );

            $filteredTotalAssignments =
                (clone $filteredBaseQuery)->count();

            $filteredCurrentAssignments =
                (clone $filteredBaseQuery)
                    ->whereNull('returned_date')
                    ->count();

            $filteredReturnedAssignments =
                (clone $filteredBaseQuery)
                    ->whereNotNull('returned_date')
                    ->count();
        }

        return view(
            'admin.reports.assignments.index',
            compact(
                'reportType',
                'employeeId',
                'status',
                'branchId',
                'departmentId',
                'functionUnitId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'employees',
                'branches',
                'departments',
                'functionUnits',
                'itemTypes',
                'assignments',
                'unassignedItems',
                'currentAssignments',
                'returnedAssignments',
                'totalAssignments',
                'unassignedAssets',
                'employeeAssets',
                'filteredCurrentAssignments',
                'filteredReturnedAssignments',
                'filteredTotalAssignments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT PRINT
    |--------------------------------------------------------------------------
    */

    public function assignmentPrint()
    {
        $reportType = request('report_type', 'current');

        if ($reportType === 'unassigned') {
            $unassignedQuery = Item::query()
                ->with([
                    'itemType',
                    'branch',
                    'department',
                    'currentEmployee',
                ])
                ->whereDoesntHave(
                    'assignments',
                    function ($query) {
                        $query->whereNull('returned_date');
                    }
                );

            $this->applyUnassignedReportFilters(
                $unassignedQuery,
                request('branch_id'),
                request('department_id'),
                request('item_type_id'),
                request('from_date'),
                request('to_date')
            );

            $unassignedItems = $unassignedQuery
                ->latest('id')
                ->get();

            return view(
                'admin.reports.assignments.print',
                compact(
                    'reportType',
                    'unassignedItems'
                )
            );
        }

        $assignmentQuery = ItemAssignment::query()
            ->with([
                'item.itemType',
                'employee',
                'branch',
                'department',
                'functionUnit',
            ]);

        $this->applyAssignmentReportFilters(
            $assignmentQuery,
            $reportType,
            request('employee_id'),
            request('status'),
            request('branch_id'),
            request('department_id'),
            request('function_unit_id'),
            request('item_type_id'),
            request('from_date'),
            request('to_date')
        );

        $assignments = $assignmentQuery
            ->latest('id')
            ->get();

        return view(
            'admin.reports.assignments.print',
            compact(
                'reportType',
                'assignments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT PDF
    |--------------------------------------------------------------------------
    */

    public function assignmentPdf()
    {
        $reportType = request('report_type', 'current');

        if ($reportType === 'unassigned') {
            $unassignedQuery = Item::query()
                ->with([
                    'itemType',
                    'branch',
                    'department',
                    'currentEmployee',
                ])
                ->whereDoesntHave(
                    'assignments',
                    function ($query) {
                        $query->whereNull('returned_date');
                    }
                );

            $this->applyUnassignedReportFilters(
                $unassignedQuery,
                request('branch_id'),
                request('department_id'),
                request('item_type_id'),
                request('from_date'),
                request('to_date')
            );

            $unassignedItems = $unassignedQuery
                ->latest('id')
                ->get();

            $pdf = Pdf::loadView(
                'admin.reports.assignments.pdf',
                compact(
                    'reportType',
                    'unassignedItems'
                )
            );
        } else {
            $assignmentQuery = ItemAssignment::query()
                ->with([
                    'item.itemType',
                    'employee',
                    'branch',
                    'department',
                    'functionUnit',
                ]);

            $this->applyAssignmentReportFilters(
                $assignmentQuery,
                $reportType,
                request('employee_id'),
                request('status'),
                request('branch_id'),
                request('department_id'),
                request('function_unit_id'),
                request('item_type_id'),
                request('from_date'),
                request('to_date')
            );

            $assignments = $assignmentQuery
                ->latest('id')
                ->get();

            $pdf = Pdf::loadView(
                'admin.reports.assignments.pdf',
                compact(
                    'reportType',
                    'assignments'
                )
            );
        }

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'CPC_Assignment_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT EXCEL
    |--------------------------------------------------------------------------
    */

    public function assignmentExcel()
    {
        $filters = [
            'report_type' => request('report_type', 'current'),
            'employee_id' => request('employee_id'),
            'status' => request('status'),
            'branch_id' => request('branch_id'),
            'department_id' => request('department_id'),
            'function_unit_id' => request('function_unit_id'),
            'item_type_id' => request('item_type_id'),
            'from_date' => request('from_date'),
            'to_date' => request('to_date'),
        ];

        return Excel::download(
            new AssignmentReportExport($filters),
            'CPC_Assignment_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT FILTER HELPER
    |--------------------------------------------------------------------------
    */

    private function applyAssignmentReportFilters(
        $query,
        $reportType,
        $employeeId = null,
        $status = null,
        $branchId = null,
        $departmentId = null,
        $functionUnitId = null,
        $itemTypeId = null,
        $fromDate = null,
        $toDate = null
    ) {
        if ($reportType === 'current') {
            $query->whereNull('returned_date');
        }

        if ($reportType === 'employee' && $employeeId) {
            $query->where('employee_id', $employeeId);
        }

        if ($status === 'issued') {
            $query->whereNull('returned_date');
        }

        if ($status === 'returned') {
            $query->whereNotNull('returned_date');
        }

        if ($employeeId && $reportType !== 'unassigned') {
            $query->where('employee_id', $employeeId);
        }

        if ($branchId && $reportType !== 'unassigned') {
            $query->where('branch_id', $branchId);
        }

        if ($departmentId && $reportType !== 'unassigned') {
            $query->where('department_id', $departmentId);
        }

        if ($functionUnitId && $reportType !== 'unassigned') {
            $query->where('function_unit_id', $functionUnitId);
        }

        if ($itemTypeId && $reportType !== 'unassigned') {
            $query->whereHas(
                'item',
                function ($itemQuery) use ($itemTypeId) {
                    $itemQuery->where(
                        'item_type_id',
                        $itemTypeId
                    );
                }
            );
        }

        if ($fromDate && $reportType !== 'unassigned') {
            $query->whereDate(
                'assigned_date',
                '>=',
                $fromDate
            );
        }

        if ($toDate && $reportType !== 'unassigned') {
            $query->whereDate(
                'assigned_date',
                '<=',
                $toDate
            );
        }

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | UNASSIGNED ASSET FILTER HELPER
    |--------------------------------------------------------------------------
    */

    private function applyUnassignedReportFilters(
        $query,
        $branchId = null,
        $departmentId = null,
        $itemTypeId = null,
        $fromDate = null,
        $toDate = null
    ) {
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($itemTypeId) {
            $query->where('item_type_id', $itemTypeId);
        }

        if ($fromDate) {
            $query->whereDate(
                'created_at',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $query->whereDate(
                'created_at',
                '<=',
                $toDate
            );
        }

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY SUMMARY REPORT
    |--------------------------------------------------------------------------
    */

    public function inventorySummary()
    {
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $status = request('status');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $inventoryQuery = Item::query()
            ->with([
                'itemType',
                'branch',
                'department',
                'currentEmployee',
            ]);

        $this->applyInventoryReportFilters(
            $inventoryQuery,
            $branchId,
            $departmentId,
            $itemTypeId,
            $status,
            $fromDate,
            $toDate
        );

        $summaryQuery = clone $inventoryQuery;

        $items = (clone $inventoryQuery)
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $totalAssets = (clone $summaryQuery)->count();

        $inUse = (clone $summaryQuery)
            ->where('status', 'in_use')
            ->count();

        $available = (clone $summaryQuery)
            ->where('status', 'available')
            ->count();

        $underRepair = (clone $summaryQuery)
            ->where('status', 'repair')
            ->count();

        $rdd = (clone $summaryQuery)
            ->where('status', 'rdd')
            ->count();

        return view(
            'admin.reports.inventory.summary',
            compact(
                'branches',
                'departments',
                'itemTypes',
                'items',
                'totalAssets',
                'inUse',
                'available',
                'underRepair',
                'rdd'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY PRINT
    |--------------------------------------------------------------------------
    */

    public function inventoryPrint()
    {
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $status = request('status');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $inventoryQuery = Item::query()
            ->with([
                'itemType',
                'branch',
                'department',
                'currentEmployee',
            ]);

        $this->applyInventoryReportFilters(
            $inventoryQuery,
            $branchId,
            $departmentId,
            $itemTypeId,
            $status,
            $fromDate,
            $toDate
        );

        $items = $inventoryQuery
            ->latest('id')
            ->get();

        $totalAssets = $items->count();

        $inUse = $items
            ->where('status', 'in_use')
            ->count();

        $available = $items
            ->where('status', 'available')
            ->count();

        $underRepair = $items
            ->where('status', 'repair')
            ->count();

        $rdd = $items
            ->where('status', 'rdd')
            ->count();

        return view(
            'admin.reports.inventory.print',
            compact(
                'items',
                'totalAssets',
                'inUse',
                'available',
                'underRepair',
                'rdd',
                'fromDate',
                'toDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY PDF
    |--------------------------------------------------------------------------
    */

    public function inventoryPdf()
    {
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $status = request('status');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $inventoryQuery = Item::query()
            ->with([
                'itemType',
                'branch',
                'department',
                'currentEmployee',
            ]);

        $this->applyInventoryReportFilters(
            $inventoryQuery,
            $branchId,
            $departmentId,
            $itemTypeId,
            $status,
            $fromDate,
            $toDate
        );

        $items = $inventoryQuery
            ->latest('id')
            ->get();

        $totalAssets = $items->count();

        $inUse = $items
            ->where('status', 'in_use')
            ->count();

        $available = $items
            ->where('status', 'available')
            ->count();

        $underRepair = $items
            ->where('status', 'repair')
            ->count();

        $rdd = $items
            ->where('status', 'rdd')
            ->count();

        $pdf = Pdf::loadView(
            'admin.reports.inventory.pdf',
            compact(
                'items',
                'totalAssets',
                'inUse',
                'available',
                'underRepair',
                'rdd',
                'branchId',
                'departmentId',
                'itemTypeId',
                'status',
                'fromDate',
                'toDate'
            )
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'CPC_Inventory_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY EXCEL
    |--------------------------------------------------------------------------
    */

    public function inventoryExcel()
    {
        $filters = [
            'branch_id' => request('branch_id'),
            'department_id' => request('department_id'),
            'item_type_id' => request('item_type_id'),
            'status' => request('status'),
            'from_date' => request('from_date'),
            'to_date' => request('to_date'),
        ];

        return Excel::download(
            new InventoryReportExport($filters),
            'CPC_Inventory_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY FILTER HELPER
    |--------------------------------------------------------------------------
    */

    private function applyInventoryReportFilters(
        $query,
        $branchId = null,
        $departmentId = null,
        $itemTypeId = null,
        $status = null,
        $fromDate = null,
        $toDate = null
    ) {
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($itemTypeId) {
            $query->where('item_type_id', $itemTypeId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($fromDate) {
            $query->whereDate(
                'created_at',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $query->whereDate(
                'created_at',
                '<=',
                $toDate
            );
        }

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR REPORTS
    |--------------------------------------------------------------------------
    */

    public function repairReports()
    {
        $reportType = request(
            'report_type',
            'summary'
        );

        return match ($reportType) {
            'pending_completed' =>
                $this->repairPendingCompleted(),

            'cost' =>
                $this->repairCost(),

            default =>
                $this->repairSummary(),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR SUMMARY
    |--------------------------------------------------------------------------
    */

    public function repairSummary()
    {
        $reportType = 'summary';

        $status = request('status');
        $employeeId = request('employee_id');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $employees = Employee::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $repairQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $repairQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        $summaryQuery = clone $repairQuery;

        $repairs = (clone $repairQuery)
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $totalRepairs =
            (clone $summaryQuery)->count();

        $pendingRepairs =
            (clone $summaryQuery)
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'sent_for_repair',
                        'under_repair',
                    ]
                )
                ->count();

        $completedRepairs =
            (clone $summaryQuery)
                ->whereIn(
                    'status',
                    [
                        'completed',
                        'returned',
                    ]
                )
                ->count();

        $cancelledRepairs =
            (clone $summaryQuery)
                ->where(
                    'status',
                    'cancelled'
                )
                ->count();

        $totalRepairCost =
            (clone $summaryQuery)
                ->sum('repair_cost');

        $averageRepairCost =
            $totalRepairs > 0
                ? $totalRepairCost / $totalRepairs
                : 0;

        $completedForTurnaround =
            (clone $summaryQuery)
                ->whereIn(
                    'status',
                    [
                        'completed',
                        'returned',
                    ]
                )
                ->whereNotNull('date_sent')
                ->whereNotNull('date_returned')
                ->get([
                    'date_sent',
                    'date_returned',
                ]);

        $averageTurnaroundDays = 0;

        if ($completedForTurnaround->isNotEmpty()) {
            $totalTurnaroundDays = 0;

            foreach ($completedForTurnaround as $repair) {
                if (
                    $repair->date_sent &&
                    $repair->date_returned
                ) {
                    $totalTurnaroundDays +=
                        Carbon::parse(
                            $repair->date_sent
                        )->diffInDays(
                            Carbon::parse(
                                $repair->date_returned
                            )
                        );
                }
            }

            $averageTurnaroundDays =
                $totalTurnaroundDays /
                $completedForTurnaround->count();
        }

        return view(
            'admin.reports.repairs.summary',
            compact(
                'reportType',
                'status',
                'employeeId',
                'branchId',
                'departmentId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'employees',
                'branches',
                'departments',
                'itemTypes',
                'repairs',
                'totalRepairs',
                'pendingRepairs',
                'completedRepairs',
                'cancelledRepairs',
                'totalRepairCost',
                'averageRepairCost',
                'averageTurnaroundDays'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PENDING & COMPLETED REPAIR REPORT
    |--------------------------------------------------------------------------
    */

    public function repairPendingCompleted()
    {
        $reportType = 'pending_completed';

        $status = request('status');
        $employeeId = request('employee_id');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $employees = Employee::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $repairQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $repairQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        $repairQuery->whereIn(
            'status',
            [
                'pending',
                'sent_for_repair',
                'under_repair',
                'completed',
                'returned',
            ]
        );

        $repairs = $repairQuery
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $summaryQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $summaryQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        $summaryQuery->whereIn(
            'status',
            [
                'pending',
                'sent_for_repair',
                'under_repair',
                'completed',
                'returned',
            ]
        );

        $totalRepairs =
            (clone $summaryQuery)->count();

        $pendingRepairs =
            (clone $summaryQuery)
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'sent_for_repair',
                        'under_repair',
                    ]
                )
                ->count();

        $completedRepairs =
            (clone $summaryQuery)
                ->whereIn(
                    'status',
                    [
                        'completed',
                        'returned',
                    ]
                )
                ->count();

        $totalRepairCost =
            (clone $summaryQuery)
                ->sum('repair_cost');

        return view(
            'admin.reports.repairs.pending-completed',
            compact(
                'reportType',
                'status',
                'employeeId',
                'branchId',
                'departmentId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'employees',
                'branches',
                'departments',
                'itemTypes',
                'repairs',
                'totalRepairs',
                'pendingRepairs',
                'completedRepairs',
                'totalRepairCost'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR COST REPORT
    |--------------------------------------------------------------------------
    */

    public function repairCost()
    {
        $reportType = 'cost';

        $status = request('status');
        $employeeId = request('employee_id');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $employees = Employee::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $repairQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $repairQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        $repairs = (clone $repairQuery)
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $totalRepairCost =
            (clone $repairQuery)
                ->sum('repair_cost');

        $totalRepairs =
            (clone $repairQuery)
                ->count();

        $averageRepairCost =
            $totalRepairs > 0
                ? $totalRepairCost / $totalRepairs
                : 0;

        $highestRepairCost =
            (clone $repairQuery)
                ->max('repair_cost') ?? 0;

        $completedRepairCost =
            (clone $repairQuery)
                ->whereIn(
                    'status',
                    [
                        'completed',
                        'returned',
                    ]
                )
                ->sum('repair_cost');

        $pendingRepairCost =
            (clone $repairQuery)
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'sent_for_repair',
                        'under_repair',
                    ]
                )
                ->sum('repair_cost');

        return view(
            'admin.reports.repairs.cost',
            compact(
                'reportType',
                'status',
                'employeeId',
                'branchId',
                'departmentId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'employees',
                'branches',
                'departments',
                'itemTypes',
                'repairs',
                'totalRepairCost',
                'totalRepairs',
                'averageRepairCost',
                'highestRepairCost',
                'completedRepairCost',
                'pendingRepairCost'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR PRINT
    |--------------------------------------------------------------------------
    */

    public function repairPrint()
    {
        $reportType = request(
            'report_type',
            'summary'
        );

        $status = request('status');
        $employeeId = request('employee_id');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $employees = Employee::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $itemTypes = ItemType::orderBy('name')->get();

        $repairQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $repairQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        if ($reportType === 'cost') {
            $repairQuery->orderByDesc('repair_cost');
        } else {
            $repairQuery->latest('id');
        }

        $repairs = $repairQuery->get();

        return view(
            'admin.reports.repairs.print',
            compact(
                'reportType',
                'status',
                'employeeId',
                'branchId',
                'departmentId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'employees',
                'branches',
                'departments',
                'itemTypes',
                'repairs'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR PDF
    |--------------------------------------------------------------------------
    */

    public function repairPdf()
    {
        $reportType = request(
            'report_type',
            'summary'
        );

        $status = request('status');
        $employeeId = request('employee_id');
        $branchId = request('branch_id');
        $departmentId = request('department_id');
        $itemTypeId = request('item_type_id');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $repairQuery = $this->repairReportQuery();

        $this->applyRepairReportFilters(
            $repairQuery,
            $status,
            $employeeId,
            $branchId,
            $departmentId,
            $itemTypeId,
            $fromDate,
            $toDate
        );

        if ($reportType === 'cost') {
            $repairQuery->orderByDesc('repair_cost');
        } else {
            $repairQuery->latest('id');
        }

        $repairs = $repairQuery->get();

        $pdf = Pdf::loadView(
            'admin.reports.repairs.pdf',
            compact(
                'reportType',
                'status',
                'employeeId',
                'branchId',
                'departmentId',
                'itemTypeId',
                'fromDate',
                'toDate',
                'repairs'
            )
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'CPC_Repair_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR EXCEL
    |--------------------------------------------------------------------------
    */

    public function repairExcel()
    {
        $reportType = request(
            'report_type',
            'summary'
        );

        $filters = [
            'report_type' => $reportType,
            'status' => request('status'),
            'employee_id' => request('employee_id'),
            'branch_id' => request('branch_id'),
            'department_id' => request('department_id'),
            'item_type_id' => request('item_type_id'),
            'from_date' => request('from_date'),
            'to_date' => request('to_date'),
        ];

        return Excel::download(
            new RepairReportExport($filters),
            'CPC_Repair_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR BASE QUERY
    |--------------------------------------------------------------------------
    */

    private function repairReportQuery()
    {
        return Repair::query()
            ->with([
                'item.itemType',
                'item.branch',
                'item.department',
                'item.currentEmployee',
                'employee',
                'collection',
                'repairSpareParts.sparePart',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR FILTER HELPER
    |--------------------------------------------------------------------------
    */

    private function applyRepairReportFilters(
        $query,
        $status = null,
        $employeeId = null,
        $branchId = null,
        $departmentId = null,
        $itemTypeId = null,
        $fromDate = null,
        $toDate = null
    ) {
        if ($status) {
            $query->where('status', $status);
        }

        if ($employeeId) {
            $query->where(
                'employee_id',
                $employeeId
            );
        }

        if ($branchId) {
            $query->whereHas(
                'item',
                function ($itemQuery) use ($branchId) {
                    $itemQuery->where(
                        'branch_id',
                        $branchId
                    );
                }
            );
        }

        if ($departmentId) {
            $query->whereHas(
                'item',
                function ($itemQuery) use ($departmentId) {
                    $itemQuery->where(
                        'department_id',
                        $departmentId
                    );
                }
            );
        }

        if ($itemTypeId) {
            $query->whereHas(
                'item',
                function ($itemQuery) use ($itemTypeId) {
                    $itemQuery->where(
                        'item_type_id',
                        $itemTypeId
                    );
                }
            );
        }

        if ($fromDate) {
            $query->whereDate(
                'date_sent',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $query->whereDate(
                'date_sent',
                '<=',
                $toDate
            );
        }

        return $query;
    }

/*
|--------------------------------------------------------------------------
| RDD REPORTS
|--------------------------------------------------------------------------
*/

public function rddReports(Request $request)
{
    $reportType = $request->get(
        'report_type',
        'summary'
    );

    if (!in_array($reportType, [
        'summary',
        'history',
        'active',
        'location',
    ], true)) {
        $reportType = 'summary';
    }

    return match ($reportType) {

        'history' =>
            $this->rddHistoryReport($request),

        'active' =>
            $this->rddActiveReport($request),

        'location' =>
            $this->rddLocationReport($request),

        default =>
            $this->rddSummaryReport($request),
    };
}


/*
|--------------------------------------------------------------------------
| RDD SUMMARY REPORT
|--------------------------------------------------------------------------
*/

private function rddSummaryReport(Request $request)
{
    $data = $this->buildRddReportData(
        'summary',
        $request
    );

    return view(
        'admin.reports.rdd.index',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RDD ACTIVE REPORT
|--------------------------------------------------------------------------
*/

private function rddActiveReport(Request $request)
{
    $data = $this->buildRddReportData(
        'active',
        $request
    );

    return view(
        'admin.reports.rdd.index',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RDD HISTORY REPORT
|--------------------------------------------------------------------------
*/

private function rddHistoryReport(Request $request)
{
    $data = $this->buildRddReportData(
        'history',
        $request
    );

    return view(
        'admin.reports.rdd.index',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RDD LOCATION REPORT
|--------------------------------------------------------------------------
*/

private function rddLocationReport(Request $request)
{
    $data = $this->buildRddReportData(
        'location',
        $request
    );

    return view(
        'admin.reports.rdd.index',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RDD PDF
|--------------------------------------------------------------------------
*/

public function rddPdf(Request $request)
{
    $reportType = $request->get(
        'report_type',
        'summary'
    );

    $data = $this->buildRddReportData(
        $reportType,
        $request
    );

    $pdf = Pdf::loadView(
        'admin.reports.rdd.pdf',
        $data
    )->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'CPC_RDD_' .
        $reportType .
        '_Report_' .
        now()->format('Y-m-d_H-i-s') .
        '.pdf'
    );
}


/*
|--------------------------------------------------------------------------
| RDD EXCEL
|--------------------------------------------------------------------------
*/

public function rddExcel(Request $request)
{
    $reportType = $request->get(
        'report_type',
        'summary'
    );

    return Excel::download(
        new RddReportExport([
            ...$request->all(),
            'report_type' => $reportType,
        ]),
        'CPC_RDD_' .
        $reportType .
        '_Report_' .
        now()->format('Y-m-d_H-i-s') .
        '.xlsx'
    );
}


/*
|--------------------------------------------------------------------------
| RDD PRINT
|--------------------------------------------------------------------------
*/

public function rddPrint(Request $request)
{
    $reportType = $request->get(
        'report_type',
        'summary'
    );

    $data = $this->buildRddReportData(
        $reportType,
        $request
    );

    return view(
        'admin.reports.rdd.print',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| BUILD RDD REPORT DATA
|--------------------------------------------------------------------------
*/

private function buildRddReportData(
    string $reportType,
    Request $request
): array {

    /*
    |--------------------------------------------------------------------------
    | VALID REPORT TYPE
    |--------------------------------------------------------------------------
    */

    if (!in_array($reportType, [
        'summary',
        'history',
        'active',
        'location',
    ], true)) {
        $reportType = 'summary';
    }


    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    $search = trim(
        (string) $request->get(
            'search',
            ''
        )
    );

    $status = $request->get(
        'status'
    );

    $employeeId = $request->get(
        'employee_id'
    );

    $branchId = $request->get(
        'branch_id'
    );

    $departmentId = $request->get(
        'department_id'
    );

    $itemTypeId = $request->get(
        'item_type_id'
    );

    $active = $request->get(
        'active'
    );

    $fromDate = $request->get(
        'from_date'
    );

    $toDate = $request->get(
        'to_date'
    );


    /*
    |--------------------------------------------------------------------------
    | BASE QUERY
    |--------------------------------------------------------------------------
    */

    $query = RddTransaction::query()
        ->with([
            'item.itemType',
            'item.branch',
            'item.department',
            'item.functionUnit',
            'item.currentEmployee',
            'employee',
            'histories',
        ]);


    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'reference_no',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'reason',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'recommendation',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'remarks',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas(
                    'item',
                    function ($itemQuery) use ($search) {

                        $itemQuery
                            ->where(
                                'asset_number',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'serial_number',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'ip_address',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'workstation_id',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                )

                ->orWhereHas(
                    'employee',
                    function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'epf_no',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if (
        $status !== null &&
        $status !== ''
    ) {

        $query->where(
            'status',
            $status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE FILTER
    |--------------------------------------------------------------------------
    */

    if (
        $employeeId !== null &&
        $employeeId !== ''
    ) {

        $query->where(
            'employee_id',
            $employeeId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BRANCH FILTER
    |--------------------------------------------------------------------------
    */

    if (
        $branchId !== null &&
        $branchId !== ''
    ) {

        $query->whereHas(
            'item',
            function ($itemQuery) use ($branchId) {

                $itemQuery->where(
                    'branch_id',
                    $branchId
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT FILTER
    |--------------------------------------------------------------------------
    */

    if (
        $departmentId !== null &&
        $departmentId !== ''
    ) {

        $query->whereHas(
            'item',
            function ($itemQuery) use ($departmentId) {

                $itemQuery->where(
                    'department_id',
                    $departmentId
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM TYPE FILTER
    |--------------------------------------------------------------------------
    */

    if (
        $itemTypeId !== null &&
        $itemTypeId !== ''
    ) {

        $query->whereHas(
            'item',
            function ($itemQuery) use ($itemTypeId) {

                $itemQuery->where(
                    'item_type_id',
                    $itemTypeId
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE / INACTIVE
    |--------------------------------------------------------------------------
    |
    | Request parameter:
    | active
    |
    | Database column:
    | is_active
    |
    */

    if (
        $active !== null &&
        $active !== ''
    ) {

        $activeValue = strtolower(
            (string) $active
        );

        if (
            in_array(
                $activeValue,
                [
                    '1',
                    'true',
                    'active',
                ],
                true
            )
        ) {

            $query->where(
                'is_active',
                true
            );

        } elseif (
            in_array(
                $activeValue,
                [
                    '0',
                    'false',
                    'inactive',
                ],
                true
            )
        ) {

            $query->where(
                'is_active',
                false
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE REPORT
    |--------------------------------------------------------------------------
    */

    if ($reportType === 'active') {

        $query->where(
            'is_active',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

    if (
        $fromDate !== null &&
        $fromDate !== ''
    ) {

        $query->whereDate(
            'rdd_date',
            '>=',
            $fromDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

    if (
        $toDate !== null &&
        $toDate !== ''
    ) {

        $query->whereDate(
            'rdd_date',
            '<=',
            $toDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ORDERING
    |--------------------------------------------------------------------------
    */

    $query
        ->orderByDesc(
            'rdd_date'
        )
        ->orderByDesc(
            'id'
        );


    /*
    |--------------------------------------------------------------------------
    | SUMMARY QUERY
    |--------------------------------------------------------------------------
    */

    $summaryQuery = clone $query;


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $transactions = (clone $query)
        ->paginate(25)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | KPIs
    |--------------------------------------------------------------------------
    */

    $totalRdd =
        (clone $summaryQuery)
            ->count();

    $activeRdd =
        (clone $summaryQuery)
            ->where(
                'is_active',
                true
            )
            ->count();

    $inactiveRdd =
        (clone $summaryQuery)
            ->where(
                'is_active',
                false
            )
            ->count();

    $pendingRdd =
        (clone $summaryQuery)
            ->whereIn(
                'status',
                [
                    'pending',
                    'approved',
                    'dispatched',
                ]
            )
            ->count();

    $receivedRdd =
        (clone $summaryQuery)
            ->whereIn(
                'status',
                [
                    'received',
                    'inspection',
                    'in_progress',
                ]
            )
            ->count();

    $completedRdd =
        (clone $summaryQuery)
            ->whereIn(
                'status',
                [
                    'completed',
                    'returned',
                ]
            )
            ->count();

    $beyondRepairRdd =
        (clone $summaryQuery)
            ->where(
                'status',
                'beyond_repair'
            )
            ->count();

    $rejectedRdd =
        (clone $summaryQuery)
            ->whereIn(
                'status',
                [
                    'rejected',
                    'cancelled',
                ]
            )
            ->count();

    $recommendationRecorded =
        (clone $summaryQuery)
            ->whereNotNull(
                'recommendation'
            )
            ->where(
                'recommendation',
                '<>',
                ''
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | DROPDOWNS
    |--------------------------------------------------------------------------
    */

    $employees =
        Employee::orderBy(
            'name'
        )->get();

    $branches =
        Branch::orderBy(
            'name'
        )->get();

    $departments =
        Department::orderBy(
            'name'
        )->get();

    $itemTypes =
        ItemType::orderBy(
            'name'
        )->get();


    /*
    |--------------------------------------------------------------------------
    | LOCATION SUMMARY
    |--------------------------------------------------------------------------
    */

    $locationTransactions =
        (clone $summaryQuery)
            ->with([
                'item.branch',
                'item.department',
            ])
            ->get([
                'id',
                'item_id',
                'status',
                'is_active',
            ]);


    $locationRows =
        $locationTransactions
            ->groupBy(
                function ($rdd) {

                    return $rdd
                        ->item
                        ?->branch
                        ?->id
                        ?? 0;
                }
            )
            ->map(
                function ($records) {

                    $branch = $records
                        ->first()
                        ?->item
                        ?->branch;


                    $departmentNames =
                        $records
                            ->map(
                                function ($rdd) {

                                    return $rdd
                                        ->item
                                        ?->department
                                        ?->name;
                                }
                            )
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values()
                            ->implode(', ');


                    return [

                        'branch_id' =>
                            $branch?->id,

                        'branch_name' =>
                            $branch?->name
                            ?? 'Unknown Location',

                        'total' =>
                            $records->count(),

                        'active' =>
                            $records
                                ->where(
                                    'is_active',
                                    true
                                )
                                ->count(),

                        'pending' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'pending',
                                        'approved',
                                        'dispatched',
                                    ]
                                )
                                ->count(),

                        'received' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'received',
                                        'inspection',
                                        'in_progress',
                                    ]
                                )
                                ->count(),

                        'completed' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'completed',
                                        'returned',
                                    ]
                                )
                                ->count(),

                        'beyond_repair' =>
                            $records
                                ->where(
                                    'status',
                                    'beyond_repair'
                                )
                                ->count(),

                        'rejected' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'rejected',
                                        'cancelled',
                                    ]
                                )
                                ->count(),

                        'departments' =>
                            $departmentNames,
                    ];
                }
            )
            ->sortBy(
                'branch_name'
            )
            ->values();


    /*
    |--------------------------------------------------------------------------
    | RETURN DATA
    |--------------------------------------------------------------------------
    */

    return [

        'reportType' =>
            $reportType,

        'search' =>
            $search,

        'status' =>
            $status,

        'employeeId' =>
            $employeeId,

        'branchId' =>
            $branchId,

        'departmentId' =>
            $departmentId,

        'itemTypeId' =>
            $itemTypeId,

        'active' =>
            $active,

        'fromDate' =>
            $fromDate,

        'toDate' =>
            $toDate,

        'employees' =>
            $employees,

        'branches' =>
            $branches,

        'departments' =>
            $departments,

        'itemTypes' =>
            $itemTypes,

        'rddTransactions' =>
            $transactions,

        'transactions' =>
            $transactions,

        'totalRdd' =>
            $totalRdd,

        'activeRdd' =>
            $activeRdd,

        'inactiveRdd' =>
            $inactiveRdd,

        'pendingRdd' =>
            $pendingRdd,

        'receivedRdd' =>
            $receivedRdd,

        'completedRdd' =>
            $completedRdd,

        'beyondRepairRdd' =>
            $beyondRepairRdd,

        'beyondRepair' =>
            $beyondRepairRdd,

        'rejectedRdd' =>
            $rejectedRdd,

        'recommendationRecorded' =>
            $recommendationRecorded,

        'locationRows' =>
            $locationRows,
    ];
}


/*
|--------------------------------------------------------------------------
| TEMPORARY ITEM ISSUE REPORTS
|--------------------------------------------------------------------------
*/

public function temporaryItemReports(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'summary'
    );

    if (!in_array($reportType, [
        'summary',
        'current_returned',
        'overdue',
    ], true)) {
        $reportType = 'summary';
    }

    $data = $this->buildTemporaryItemReportData(
        $reportType,
        $request
    );

    return view(
        'admin.reports.temporary-items.index',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| TEMPORARY ITEM PRINT
|--------------------------------------------------------------------------
*/

public function temporaryItemPrint(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'summary'
    );

    if (!in_array($reportType, [
        'summary',
        'current_returned',
        'overdue',
    ], true)) {
        $reportType = 'summary';
    }

    $data = $this->buildTemporaryItemReportData(
        $reportType,
        $request,
        false
    );

    return view(
        'admin.reports.temporary-items.print',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| TEMPORARY ITEM PDF
|--------------------------------------------------------------------------
*/

public function temporaryItemPdf(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'summary'
    );

    if (!in_array($reportType, [
        'summary',
        'current_returned',
        'overdue',
    ], true)) {
        $reportType = 'summary';
    }

    $data = $this->buildTemporaryItemReportData(
        $reportType,
        $request,
        false
    );

    $pdf = Pdf::loadView(
        'admin.reports.temporary-items.pdf',
        $data
    )->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'CPC_Temporary_Item_' .
        $reportType .
        '_Report_' .
        now()->format('Y-m-d_H-i-s') .
        '.pdf'
    );
}


/*
|--------------------------------------------------------------------------
| TEMPORARY ITEM EXCEL
|--------------------------------------------------------------------------
*/

public function temporaryItemExcel(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'summary'
    );

    if (!in_array($reportType, [
        'summary',
        'current_returned',
        'overdue',
    ], true)) {
        $reportType = 'summary';
    }

    return Excel::download(
        new TemporaryItemReportExport(
            $request
        ),
        'CPC_Temporary_Item_' .
        $reportType .
        '_Report_' .
        now()->format('Y-m-d_H-i-s') .
        '.xlsx'
    );
}


/*
|--------------------------------------------------------------------------
| BUILD TEMPORARY ITEM REPORT DATA
|--------------------------------------------------------------------------
*/

private function buildTemporaryItemReportData(
    string $reportType,
    Request $request,
    bool $paginate = true
): array {

    /*
    |--------------------------------------------------------------------------
    | VALID REPORT TYPE
    |--------------------------------------------------------------------------
    */

    if (!in_array($reportType, [
        'summary',
        'current_returned',
        'overdue',
    ], true)) {
        $reportType = 'summary';
    }


    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    $search = trim(
        (string) $request->get(
            'search',
            ''
        )
    );

    $itemCode = trim(
        (string) $request->get(
            'item_code',
            ''
        )
    );

    $itemType = $request->get(
        'item_type'
    );

    $departmentId = $request->get(
        'department_id'
    );

    $functionUnitId = $request->get(
        'function_unit_id'
    );

    $requestingEmployeeId =
        $request->get(
            'requesting_employee_id'
        );

    /*
    |--------------------------------------------------------------------------
    | TEMPORARY ITEM ISSUE ID
    |--------------------------------------------------------------------------
    |
    | Allows the report to target one specific Temporary Item Issue.
    |
    */

    $temporaryItemIssueId =
        $request->get(
            'temporary_item_issue_id'
        );

    $event = trim(
        (string) $request->get(
            'event',
            ''
        )
    );

    $status = $request->get(
        'status'
    );

    $fromDate = $request->get(
        'from_date'
    );

    $toDate = $request->get(
        'to_date'
    );


    /*
    |--------------------------------------------------------------------------
    | OVERDUE THRESHOLD
    |--------------------------------------------------------------------------
    |
    | There is no due_date column.
    |
    | Overdue means:
    |
    | status = issued
    | AND issue_datetime is older than the configured threshold.
    |
    */

    $overdueDays = max(
        1,
        min(
            365,
            (int) $request->get(
                'overdue_days',
                7
            )
        )
    );


    /*
    |--------------------------------------------------------------------------
    | BASE QUERY
    |--------------------------------------------------------------------------
    */

    $query = TemporaryItemIssue::query()
        ->with([
            'department',
            'functionUnit',
            'requestingEmployee',
            'collectingEmployee',
            'returningEmployee',
        ]);


    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'item_code',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'item_type',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'event',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'remarks',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas(
                    'requestingEmployee',
                    function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'epf_no',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                )

                ->orWhereHas(
                    'collectingEmployee',
                    function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'epf_no',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                )

                ->orWhereHas(
                    'returningEmployee',
                    function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'epf_no',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM CODE
    |--------------------------------------------------------------------------
    */

    if ($itemCode !== '') {

        $query->where(
            'item_code',
            'like',
            '%' . $itemCode . '%'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM TYPE
    |--------------------------------------------------------------------------
    */

    if (
        $itemType !== null &&
        $itemType !== ''
    ) {

        $query->where(
            'item_type',
            $itemType
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    if (
        $departmentId !== null &&
        $departmentId !== ''
    ) {

        $query->where(
            'department_id',
            $departmentId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTION UNIT
    |--------------------------------------------------------------------------
    */

    if (
        $functionUnitId !== null &&
        $functionUnitId !== ''
    ) {

        $query->where(
            'function_unit_id',
            $functionUnitId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REQUESTING EMPLOYEE
    |--------------------------------------------------------------------------
    */

    if (
        $requestingEmployeeId !== null &&
        $requestingEmployeeId !== ''
    ) {

        $query->where(
            'requesting_employee_id',
            $requestingEmployeeId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TEMPORARY ITEM ISSUE
    |--------------------------------------------------------------------------
    */

    if (
        $temporaryItemIssueId !== null &&
        $temporaryItemIssueId !== ''
    ) {

        $query->where(
            'id',
            $temporaryItemIssueId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

    if ($event !== '') {

        $query->where(
            'event',
            'like',
            '%' . $event . '%'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    if (
        $status !== null &&
        $status !== ''
    ) {

        $query->where(
            'status',
            $status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    if (
        $fromDate !== null &&
        $fromDate !== ''
    ) {

        $query->whereDate(
            'issue_datetime',
            '>=',
            $fromDate
        );
    }


    if (
        $toDate !== null &&
        $toDate !== ''
    ) {

        $query->whereDate(
            'issue_datetime',
            '<=',
            $toDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT & RETURNED
    |--------------------------------------------------------------------------
    */

    if (
        $reportType === 'current_returned'
    ) {

        $query->whereIn(
            'status',
            [
                'issued',
                'received',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OVERDUE
    |--------------------------------------------------------------------------
    */

    if (
        $reportType === 'overdue'
    ) {

        $query
            ->where(
                'status',
                'issued'
            )
            ->where(
                'issue_datetime',
                '<=',
                now()->subDays(
                    $overdueDays
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

    $query
        ->orderByDesc(
            'issue_datetime'
        )
        ->orderByDesc(
            'id'
        );


    /*
    |--------------------------------------------------------------------------
    | SUMMARY QUERY
    |--------------------------------------------------------------------------
    */

    $summaryQuery = clone $query;


    /*
    |--------------------------------------------------------------------------
    | TOTAL ISSUES
    |--------------------------------------------------------------------------
    */

    $totalIssues =
        (clone $summaryQuery)
            ->count();


    /*
    |--------------------------------------------------------------------------
    | CURRENTLY ISSUED
    |--------------------------------------------------------------------------
    */

    $currentlyIssued =
        (clone $summaryQuery)
            ->where(
                'status',
                'issued'
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | RETURNED
    |--------------------------------------------------------------------------
    */

    $returnedItems =
        (clone $summaryQuery)
            ->where(
                'status',
                'received'
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | CANCELLED
    |--------------------------------------------------------------------------
    */

    $cancelledItems =
        (clone $summaryQuery)
            ->where(
                'status',
                'cancelled'
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | OVERDUE
    |--------------------------------------------------------------------------
    */

    $overdueItems =
        (clone $summaryQuery)
            ->where(
                'status',
                'issued'
            )
            ->where(
                'issue_datetime',
                '<=',
                now()->subDays(
                    $overdueDays
                )
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | TOTAL UNIQUE EVENTS
    |--------------------------------------------------------------------------
    */

    $totalEvents =
        (clone $summaryQuery)
            ->whereNotNull(
                'event'
            )
            ->where(
                'event',
                '<>',
                ''
            )
            ->distinct(
                'event'
            )
            ->count(
                'event'
            );


    /*
    |--------------------------------------------------------------------------
    | UNIQUE REQUESTING EMPLOYEES
    |--------------------------------------------------------------------------
    */

    $uniqueEmployees =
        (clone $summaryQuery)
            ->whereNotNull(
                'requesting_employee_id'
            )
            ->distinct(
                'requesting_employee_id'
            )
            ->count(
                'requesting_employee_id'
            );


    /*
    |--------------------------------------------------------------------------
    | REPORT RECORDS
    |--------------------------------------------------------------------------
    */

    if ($paginate) {

        $temporaryIssues =
            (clone $query)
                ->paginate(25)
                ->withQueryString();

    } else {

        $temporaryIssues =
            (clone $query)
                ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | TEMPORARY ITEM ISSUE DROPDOWN
    |--------------------------------------------------------------------------
    |
    | This collection is intentionally independent from the filtered
    | report query so that all Temporary Item Issues remain available
    | in the selector.
    |
    */

    $temporaryItemIssues =
        TemporaryItemIssue::query()
            ->with([
                'department',
                'functionUnit',
                'requestingEmployee',
            ])
            ->orderByDesc(
                'issue_datetime'
            )
            ->orderByDesc(
                'id'
            )
            ->get();


    /*
    |--------------------------------------------------------------------------
    | FILTER DROPDOWNS
    |--------------------------------------------------------------------------
    */

    $departments =
        Department::orderBy(
            'name'
        )->get();

    $functionUnits =
        FunctionUnit::orderBy(
            'name'
        )->get();

    $employees =
        Employee::orderBy(
            'name'
        )->get();


    /*
    |--------------------------------------------------------------------------
    | TEMPORARY ITEM TYPES
    |--------------------------------------------------------------------------
    */

    $temporaryItemTypes = [
        'PC',
        'Projector',
        'Laptop',
    ];


    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    $events =
        TemporaryItemIssue::query()
            ->whereNotNull(
                'event'
            )
            ->where(
                'event',
                '<>',
                ''
            )
            ->distinct()
            ->orderBy(
                'event'
            )
            ->pluck(
                'event'
            );


    /*
    |--------------------------------------------------------------------------
    | RETURN DATA
    |--------------------------------------------------------------------------
    */

    return [

        'reportType' =>
            $reportType,

        'search' =>
            $search,

        'itemCode' =>
            $itemCode,

        'itemType' =>
            $itemType,

        'departmentId' =>
            $departmentId,

        'functionUnitId' =>
            $functionUnitId,

        'requestingEmployeeId' =>
            $requestingEmployeeId,

        'temporaryItemIssueId' =>
            $temporaryItemIssueId,

        'event' =>
            $event,

        'status' =>
            $status,

        'fromDate' =>
            $fromDate,

        'toDate' =>
            $toDate,

        'overdueDays' =>
            $overdueDays,

        'departments' =>
            $departments,

        'functionUnits' =>
            $functionUnits,

        'employees' =>
            $employees,

        'temporaryItemTypes' =>
            $temporaryItemTypes,

        'temporaryItemIssues' =>
            $temporaryItemIssues,

        'events' =>
            $events,

        'temporaryIssues' =>
            $temporaryIssues,

        'totalIssues' =>
            $totalIssues,

        'currentlyIssued' =>
            $currentlyIssued,

        'returnedItems' =>
            $returnedItems,

        'cancelledItems' =>
            $cancelledItems,

        'overdueItems' =>
            $overdueItems,

        'totalEvents' =>
            $totalEvents,

        'uniqueEmployees' =>
            $uniqueEmployees,
    ];
}


/*
|--------------------------------------------------------------------------
| SYSTEM & AUDIT REPORTS
|--------------------------------------------------------------------------
*/

public function systemAuditReports(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'user_accounts'
    );

    $search = trim(
        (string) $request->get(
            'search',
            ''
        )
    );

    $role = $request->get(
        'role'
    );

    $accountStatus =
        $request->get(
            'account_status'
        );

    $fromDate =
        $request->get(
            'from_date'
        );

    $toDate =
        $request->get(
            'to_date'
        );


    /*
    |--------------------------------------------------------------------------
    | USER QUERY
    |--------------------------------------------------------------------------
    */

    $userQuery = User::query();


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $userQuery->where(
            function ($query) use ($search) {

                $query
                    ->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'epf_number',
                        'like',
                        '%' . $search . '%'
                    );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    if (
        $role !== null &&
        $role !== ''
    ) {

        $userQuery->where(
            'role',
            $role
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS
    |--------------------------------------------------------------------------
    */

    if (
        $accountStatus === 'active'
    ) {

        $userQuery->where(
            'is_locked',
            false
        );

    } elseif (
        $accountStatus === 'locked'
    ) {

        $userQuery->where(
            'is_locked',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FROM DATE
    |--------------------------------------------------------------------------
    */

    if ($fromDate) {

        $userQuery->whereDate(
            'created_at',
            '>=',
            $fromDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TO DATE
    |--------------------------------------------------------------------------
    */

    if ($toDate) {

        $userQuery->whereDate(
            'created_at',
            '<=',
            $toDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUMMARY QUERY
    |--------------------------------------------------------------------------
    */

    $summaryQuery =
        clone $userQuery;


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $users =
        $userQuery
            ->latest('id')
            ->paginate(25)
            ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | KPIs
    |--------------------------------------------------------------------------
    */

    $totalUsers =
        (clone $summaryQuery)
            ->count();


    $activeUsers =
        (clone $summaryQuery)
            ->where(
                'is_locked',
                false
            )
            ->count();


    $lockedUsers =
        (clone $summaryQuery)
            ->where(
                'is_locked',
                true
            )
            ->count();


    $adminUsers =
        (clone $summaryQuery)
            ->whereIn(
                'role',
                [
                    'admin',
                    'super_admin',
                ]
            )
            ->count();


    $employeeUsers =
        (clone $summaryQuery)
            ->where(
                'role',
                'employee'
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    |
    | No separate activity/audit database schema is assumed here.
    |
    | Only the confirmed User table is queried.
    |
    */

    return view(
        'admin.reports.system-audit.index',
        compact(
            'reportType',
            'search',
            'role',
            'accountStatus',
            'fromDate',
            'toDate',
            'users',
            'totalUsers',
            'activeUsers',
            'lockedUsers',
            'adminUsers',
            'employeeUsers'
        )
    );
}


/*
|--------------------------------------------------------------------------
| SYSTEM & AUDIT PRINT
|--------------------------------------------------------------------------
*/

public function systemAuditPrint(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'user_accounts'
    );

    $search = trim(
        (string) $request->get(
            'search',
            ''
        )
    );

    $role = $request->get(
        'role'
    );

    $accountStatus =
        $request->get(
            'account_status'
        );

    $fromDate =
        $request->get(
            'from_date'
        );

    $toDate =
        $request->get(
            'to_date'
        );


    $query = User::query();


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'phone',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'epf_number',
                    'like',
                    '%' . $search . '%'
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    if (
        $role !== null &&
        $role !== ''
    ) {

        $query->where(
            'role',
            $role
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS
    |--------------------------------------------------------------------------
    */

    if ($accountStatus === 'active') {

        $query->where(
            'is_locked',
            false
        );

    } elseif (
        $accountStatus === 'locked'
    ) {

        $query->where(
            'is_locked',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    if ($fromDate) {

        $query->whereDate(
            'created_at',
            '>=',
            $fromDate
        );
    }


    if ($toDate) {

        $query->whereDate(
            'created_at',
            '<=',
            $toDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    $users =
        $query
            ->latest('id')
            ->get();


    /*
    |--------------------------------------------------------------------------
    | PRINT KPIs
    |--------------------------------------------------------------------------
    */

    $totalUsers =
        $users->count();


    $activeUsers =
        $users
            ->where(
                'is_locked',
                false
            )
            ->count();


    $lockedUsers =
        $users
            ->where(
                'is_locked',
                true
            )
            ->count();


    $adminUsers =
        $users
            ->whereIn(
                'role',
                [
                    'admin',
                    'super_admin',
                ]
            )
            ->count();


    $employeeUsers =
        $users
            ->where(
                'role',
                'employee'
            )
            ->count();


    return view(
        'admin.reports.system-audit.print',
        compact(
            'reportType',
            'search',
            'role',
            'accountStatus',
            'fromDate',
            'toDate',
            'users',
            'totalUsers',
            'activeUsers',
            'lockedUsers',
            'adminUsers',
            'employeeUsers'
        )
    );
}


/*
|--------------------------------------------------------------------------
| SYSTEM & AUDIT PDF
|--------------------------------------------------------------------------
*/

public function systemAuditPdf(
    Request $request
) {

    $reportType = $request->get(
        'report_type',
        'user_accounts'
    );

    $search = trim(
        (string) $request->get(
            'search',
            ''
        )
    );

    $role = $request->get(
        'role'
    );

    $accountStatus =
        $request->get(
            'account_status'
        );

    $fromDate =
        $request->get(
            'from_date'
        );

    $toDate =
        $request->get(
            'to_date'
        );


    $query = User::query();


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'phone',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'epf_number',
                    'like',
                    '%' . $search . '%'
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    if (
        $role !== null &&
        $role !== ''
    ) {

        $query->where(
            'role',
            $role
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS
    |--------------------------------------------------------------------------
    */

    if ($accountStatus === 'active') {

        $query->where(
            'is_locked',
            false
        );

    } elseif (
        $accountStatus === 'locked'
    ) {

        $query->where(
            'is_locked',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    if ($fromDate) {

        $query->whereDate(
            'created_at',
            '>=',
            $fromDate
        );
    }


    if ($toDate) {

        $query->whereDate(
            'created_at',
            '<=',
            $toDate
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    $users =
        $query
            ->latest('id')
            ->get();


    /*
    |--------------------------------------------------------------------------
    | PDF KPIs
    |--------------------------------------------------------------------------
    */

    $totalUsers =
        $users->count();


    $activeUsers =
        $users
            ->where(
                'is_locked',
                false
            )
            ->count();


    $lockedUsers =
        $users
            ->where(
                'is_locked',
                true
            )
            ->count();


    $adminUsers =
        $users
            ->whereIn(
                'role',
                [
                    'admin',
                    'super_admin',
                ]
            )
            ->count();


    $employeeUsers =
        $users
            ->where(
                'role',
                'employee'
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'admin.reports.system-audit.pdf',
        compact(
            'reportType',
            'users',
            'totalUsers',
            'activeUsers',
            'lockedUsers',
            'adminUsers',
            'employeeUsers',
            'search',
            'role',
            'accountStatus',
            'fromDate',
            'toDate'
        )
    )->setPaper(
        'a4',
        'landscape'
    );


    return $pdf->download(
        'CPC_System_Audit_' .
        $reportType .
        '_Report_' .
        now()->format(
            'Y-m-d_H-i-s'
        ) .
        '.pdf'
    );
}
}