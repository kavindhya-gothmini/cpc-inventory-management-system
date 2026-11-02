<?php

use Illuminate\Support\Facades\DB;

$db = DB::connection('sqlsrv');

dump([
    'check' => 'Unmatched sub-function 15000',
    'employee_counts_by_availability' => $db
        ->table('TBL_HR_MAS_EMPLOYEE')
        ->where('SubFunc_Id', 15000)
        ->select('Is_Available')
        ->selectRaw('COUNT(*) AS record_count')
        ->groupBy('Is_Available')
        ->get()
        ->map(fn ($r) => [
            'is_available' => $r->Is_Available,
            'count' => $r->record_count,
        ])->all(),
    'subfunction_15000_exists' => $db
        ->table('TBL_HR_MAS_SUB_FUNCTION')
        ->where('SubFunc_Id', 15000)
        ->exists(),
]);

dump([
    'check' => 'Employee master join validation',
    'total_employees' => $db->table('TBL_HR_MAS_EMPLOYEE')->count(),
    'available_employees' => $db->table('TBL_HR_MAS_EMPLOYEE')
        ->where('Is_Available', 1)->count(),
    'available_missing_designation' => $db
        ->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_DESIGNATION as d',
            'e.Designation_Id',
            '=',
            'd.Designation_Id'
        )
        ->where('e.Is_Available', 1)
        ->whereNull('d.Designation_Id')
        ->count(),
    'available_missing_subfunction' => $db
        ->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_SUB_FUNCTION as sf',
            'e.SubFunc_Id',
            '=',
            'sf.SubFunc_Id'
        )
        ->where('e.Is_Available', 1)
        ->whereNull('sf.SubFunc_Id')
        ->count(),
    'available_missing_location' => $db
        ->table('TBL_HR_MAS_EMPLOYEE as e')
        ->leftJoin(
            'TBL_HR_MAS_SUB_FUNCTION as sf',
            'e.SubFunc_Id',
            '=',
            'sf.SubFunc_Id'
        )
        ->leftJoin(
            'TBL_HR_MAS_LOCATION as l',
            'sf.Location_Id',
            '=',
            'l.Location_Id'
        )
        ->where('e.Is_Available', 1)
        ->whereNull('l.Location_Id')
        ->count(),
]);
