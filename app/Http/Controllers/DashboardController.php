<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Item;
use App\Models\ItemAssignment;
use App\Models\ItemType;
use App\Models\Repair;
use App\Models\RddTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $role = strtolower(trim((string) $user->role));
        $isSuperAdmin = $role === 'super_admin';

        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        $branch = $branchId !== null
            ? Branch::find($branchId)
            : null;

        $isHeadOffice = (string) ($branch?->code ?? '') === '2000';

        /*
         * Preserve the existing system-wide access of the
         * unassigned Head Office Super Admin account.
         *
         * A Super Admin assigned to a branch, such as the
         * refinery (branch code 1000), is branch-restricted.
         */
        $isGlobalSuperAdmin = $isSuperAdmin
            && $branchId === null;

        $branchName = $isGlobalSuperAdmin
            ? 'All Branches'
            : ($branch?->name ?? 'Branch Not Assigned');

        /*
         * Restrict branch-owned records.
         * Missing branch assignments never grant global access,
         * except for the existing unassigned Super Admin account.
         */
        $applyBranchFilter = function ($query, string $column = 'branch_id')
            use ($isGlobalSuperAdmin, $branchId) {

                if ($isGlobalSuperAdmin) {
                    return $query;
                }

                if ($branchId === null) {
                    return $query->whereRaw('1 = 0');
                }

                return $query->where($column, $branchId);
            };

        // Inventory KPIs.
        $totalItems = $applyBranchFilter(Item::query())->count();

        $availableItems = $applyBranchFilter(Item::query())
            ->where('status', 'available')
            ->count();

        // Active assignments in the permitted branch.
        $assignmentQuery = ItemAssignment::query()
            ->whereNull('returned_date');

        if (! $isGlobalSuperAdmin) {
            if ($branchId === null) {
                $assignmentQuery->whereRaw('1 = 0');
            } else {
                $assignmentQuery->where(function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId)
                        ->orWhereHas('item', function ($itemQuery) use ($branchId) {
                            $itemQuery->where('branch_id', $branchId);
                        });
                });
            }
        }

        $assignedItems = (clone $assignmentQuery)
            ->distinct()
            ->count('item_id');

        // Employees in the permitted branch.
        $employees = $applyBranchFilter(Employee::query())->count();

        // Item types are shared reference data.
        $itemTypes = ItemType::query()->count();

        // Repairs are scoped through their associated inventory item.
        $repairQuery = Repair::query();

        if (! $isGlobalSuperAdmin) {
            if ($branchId === null) {
                $repairQuery->whereRaw('1 = 0');
            } else {
                $repairQuery->whereHas('item', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                });
            }
        }

        $repairItems = $repairQuery->count();

        // RDD transactions are scoped through their associated item.
        $rddQuery = RddTransaction::query();

        if (! $isGlobalSuperAdmin) {
            if ($branchId === null) {
                $rddQuery->whereRaw('1 = 0');
            } else {
                $rddQuery->whereHas('item', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                });
            }
        }

        $rddItems = $rddQuery->count();

        $assignedPercentage = $totalItems > 0
            ? min(100, (int) round(($assignedItems / $totalItems) * 100))
            : 0;

        $availablePercentage = $totalItems > 0
            ? min(100, (int) round(($availableItems / $totalItems) * 100))
            : 0;

        return view('dashboard', compact(
            'role',
            'isSuperAdmin',
            'isHeadOffice',
            'branchName',
            'branchId',
            'totalItems',
            'availableItems',
            'assignedItems',
            'repairItems',
            'employees',
            'itemTypes',
            'rddItems',
            'assignedPercentage',
            'availablePercentage'
        ));
    }
}
