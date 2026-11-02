<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CpcMasterDataController extends Controller
{
    /**
     * Search the locally cached CPC employee master by EPF.
     * This endpoint is read-only and does not modify operational records.
     */
    public function employeeByEpf(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user, 403);

        $epf = trim((string) $request->query('epf', ''));

        if (! preg_match('/^[0-9]{5}$/', $epf)) {
            return response()->json([
                'message' => 'Enter an EPF number containing exactly five digits.',
            ], 422);
        }

        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        $isGlobalSuperAdmin =
            strtolower((string) $user->role) === 'super_admin'
            && $branchId === null;

        // A user without a branch is never granted unrestricted access
        // unless they are the global Super Admin.
        if (! $isGlobalSuperAdmin && $branchId === null) {
            abort(403, 'Your account is not assigned to a branch.');
        }

        $employeeQuery = DB::table('cpc_master_employees as e')
            ->leftJoin(
                'cpc_master_designations as d',
                'd.source_designation_id',
                '=',
                'e.source_designation_id'
            )
            ->leftJoin(
                'cpc_master_sub_functions as sf',
                'sf.source_sub_function_id',
                '=',
                'e.source_sub_function_id'
            )
            ->leftJoin(
                'cpc_master_functions as f',
                'f.source_function_id',
                '=',
                'e.source_function_id'
            )
            ->leftJoin(
                'cpc_master_locations as l',
                'l.source_location_id',
                '=',
                'e.source_location_id'
            )
            ->where(function ($query) use ($epf) {
                $query->where('e.source_epf_no', $epf)
                    ->orWhere('e.source_epf_no', 'EPF' . $epf);
            });

        if (! $isGlobalSuperAdmin) {
            $employeeQuery->where('l.local_branch_id', (int) $branchId);
        }

        $employee = $employeeQuery->select([
            'e.source_epf_no',
            'e.name_with_initials',
            'e.full_name',
            'e.is_available as employee_is_active',
            'e.source_designation_id',
            'd.designation_name',
            'e.source_sub_function_id',
            'sf.sub_function_name',
            'e.source_function_id',
            'f.function_name',
            'e.source_location_id',
            'l.location_name',
            'l.local_branch_id',
        ])->first();

        if (! $employee) {
            return response()->json([
                'message' =>
                    'No matching CPC employee was found in your permitted branch scope.',
            ], 404);
        }

        return response()->json([
            'employee' => [
                'epf_no' => $employee->source_epf_no,
                'name_with_initials' => $employee->name_with_initials,
                'full_name' => $employee->full_name,
                'designation' => $employee->designation_name,
                'designation_id' => $employee->source_designation_id,
                'sub_function_id' => $employee->source_sub_function_id,
                'sub_function' => $employee->sub_function_name,
                'function_id' => $employee->source_function_id,
                'function' => $employee->function_name,
                'location_id' => $employee->source_location_id,
                'location' => $employee->location_name,
                'is_active' => (bool) $employee->employee_is_active,
                'local_branch_id' => $employee->local_branch_id,
                'branch_mapped' => $employee->local_branch_id !== null,
            ],
        ]);
    }
}
