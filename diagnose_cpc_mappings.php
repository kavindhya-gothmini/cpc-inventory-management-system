<?php

use Illuminate\Support\Facades\DB;

$hapis = DB::connection('sqlsrv');
$assets = DB::connection('sqlsrv_assets');

dump([
    'check' => 'Employee sub-function reference summary',
    'total_with_null_subfunction' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->whereNull('SubFunc_Id')->count(),
    'total_with_nonnull_subfunction' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->whereNotNull('SubFunc_Id')->count(),
    'available_with_null_subfunction' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->where('Is_Available', 1)
        ->whereNull('SubFunc_Id')->count(),
    'available_with_unmatched_subfunction' => $hapis->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_SUB_FUNCTION as sf',
            'e.SubFunc_Id',
            '=',
            'sf.SubFunc_Id'
        )
        ->where('e.Is_Available', 1)
        ->whereNotNull('e.SubFunc_Id')
        ->whereNull('sf.SubFunc_Id')
        ->count(),
]);

$unmatched = $hapis->table('TBL_HR_MAS_EMPLOYEE as e')
    ->leftJoin(
        'TBL_HR_MAS_SUB_FUNCTION as sf',
        'e.SubFunc_Id',
        '=',
        'sf.SubFunc_Id'
    )
    ->whereNotNull('e.SubFunc_Id')
    ->whereNull('sf.SubFunc_Id')
    ->select('e.SubFunc_Id')
    ->selectRaw('COUNT(*) AS record_count')
    ->groupBy('e.SubFunc_Id')
    ->orderBy('e.SubFunc_Id')
    ->get();

dump([
    'check' => 'Unmatched sub-function IDs (counts only)',
    'records' => $unmatched,
]);

dump([
    'check' => 'HAPIS location master',
    'locations' => $hapis->table('TBL_HR_MAS_LOCATION')
        ->select('Location_Id', 'Location_Name', 'IBM_Location', 'Is_Available')
        ->orderBy('Location_Id')
        ->get()
        ->map(fn ($r) => [
            'location_id' => $r->Location_Id,
            'location_name' => $r->Location_Name,
            'ibm_location' => $r->IBM_Location,
            'is_available' => $r->Is_Available,
        ])->all(),
]);

dump([
    'check' => 'HAPIS sub-function organisation mappings',
    'subfunctions' => $hapis->table('TBL_HR_MAS_SUB_FUNCTION as sf')
        ->leftJoin(
            'TBL_HR_MAS_FUNCTION as f',
            'sf.Function_Id',
            '=',
            'f.Function_Id'
        )
        ->leftJoin(
            'TBL_HR_MAS_LOCATION as l',
            'sf.Location_Id',
            '=',
            'l.Location_Id'
        )
        ->select(
            'sf.SubFunc_Id',
            'sf.SubFunction_Name',
            'sf.Function_Id',
            'f.Function_Name',
            'sf.Location_Id',
            'l.Location_Name',
            'sf.Is_Available'
        )
        ->orderBy('sf.Location_Id')
        ->orderBy('sf.SubFunc_Id')
        ->get()
        ->map(fn ($r) => [
            'subfunction_id' => $r->SubFunc_Id,
            'subfunction_name' => $r->SubFunction_Name,
            'function_id' => $r->Function_Id,
            'function_name' => $r->Function_Name,
            'location_id' => $r->Location_Id,
            'location_name' => $r->Location_Name,
            'is_available' => $r->Is_Available,
        ])->all(),
]);

dump([
    'check' => 'Asset Management departments',
    'departments' => $assets->table('Department')
        ->select('Id', 'Name', 'LocationCode', 'LocationType')
        ->orderBy('LocationCode')
        ->orderBy('Name')
        ->get()
        ->map(fn ($r) => [
            'department_id' => $r->Id,
            'name' => $r->Name,
            'location_code' => $r->LocationCode,
            'location_type' => $r->LocationType,
        ])->all(),
]);

dump([
    'check' => 'Source asset reconciliation fields',
    'assets' => $assets->table('Asset')
        ->select(
            'Id',
            'Type',
            'Status',
            'AssignedDepartment',
            'AssignedBranch',
            'DisposedDate'
        )
        ->orderBy('Id')
        ->get()
        ->map(fn ($r) => [
            'source_asset_id' => $r->Id,
            'type' => $r->Type,
            'status' => $r->Status,
            'assigned_department' => $r->AssignedDepartment,
            'assigned_branch' => $r->AssignedBranch,
            'disposed_date_present' => $r->DisposedDate !== null,
        ])->all(),
]);

