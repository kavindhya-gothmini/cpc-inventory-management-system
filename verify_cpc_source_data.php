<?php

use Illuminate\Support\Facades\DB;

$hapis = DB::connection('sqlsrv');
$assets = DB::connection('sqlsrv_assets');

dump([
    'check' => 'HAPIS employee master',
    'total_employees' => $hapis->table('TBL_HR_MAS_EMPLOYEE')->count(),
    'available_flag_yes' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->where('Is_Available', 1)->count(),
    'missing_epf' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->whereNull('EPF_No')->count(),
    'missing_name' => $hapis->table('TBL_HR_MAS_EMPLOYEE')
        ->whereNull('Name_with_Initials')->count(),
]);

dump([
    'check' => 'HAPIS employee relationship validation',
    'missing_designation_match' => $hapis->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_DESIGNATION as d',
            'e.Designation_Id',
            '=',
            'd.Designation_Id'
        )
        ->whereNotNull('e.Designation_Id')
        ->whereNull('d.Designation_Id')
        ->count(),
    'missing_subfunction_match' => $hapis->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_SUB_FUNCTION as sf',
            'e.SubFunc_Id',
            '=',
            'sf.SubFunc_Id'
        )
        ->whereNotNull('e.SubFunc_Id')
        ->whereNull('sf.SubFunc_Id')
        ->count(),
]);

dump([
    'check' => 'HAPIS organisation master',
    'designations' => $hapis->table('TBL_HR_MAS_DESIGNATION')->count(),
    'subfunctions' => $hapis->table('TBL_HR_MAS_SUB_FUNCTION')->count(),
    'functions' => $hapis->table('TBL_HR_MAS_FUNCTION')->count(),
    'locations' => $hapis->table('TBL_HR_MAS_LOCATION')->count(),
    'units' => $hapis->table('TBL_HR_MAS_UNIT')->count(),
]);

dump([
    'check' => 'Asset Management relationships',
    'assets' => $assets->table('Asset')->count(),
    'employees' => $assets->table('Employee')->count(),
    'departments' => $assets->table('Department')->count(),
    'tickets' => $assets->table('Ticket')->count(),
    'employees_without_department_match' => $assets->table('Employee as e')
        ->leftJoin('Department as d', 'e.DepartmentId', '=', 'd.Id')
        ->whereNotNull('e.DepartmentId')
        ->whereNull('d.Id')
        ->count(),
    'tickets_without_employee_match' => $assets->table('Ticket as t')
        ->leftJoin('Employee as e', 't.EmployeeId', '=', 'e.Id')
        ->whereNotNull('t.EmployeeId')
        ->whereNull('e.Id')
        ->count(),
    'tickets_without_asset_match' => $assets->table('Ticket as t')
        ->leftJoin('Asset as a', 't.AssetId', '=', 'a.Id')
        ->whereNotNull('t.AssetId')
        ->whereNull('a.Id')
        ->count(),
]);

dump([
    'check' => 'Asset Management department codes',
    'department_codes' => $assets->table('Department')
        ->select('LocationCode', 'LocationType')
        ->distinct()
        ->orderBy('LocationCode')
        ->get()
        ->map(fn ($row) => [
            'location_code' => $row->LocationCode,
            'location_type' => $row->LocationType,
        ])
        ->all(),
]);

