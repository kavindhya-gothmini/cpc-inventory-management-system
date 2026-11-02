<?php

use Illuminate\Support\Facades\DB;

$db = DB::connection('sqlsrv');

$duplicateEpfs = $db->table('TBL_HR_MAS_EMPLOYEE')
    ->where('Is_Available', 1)
    ->select('EPF_No')
    ->groupBy('EPF_No')
    ->havingRaw('COUNT(*) > 1')
    ->get();

$missingEpfs = $db->table('TBL_HR_MAS_EMPLOYEE')
    ->where('Is_Available', 1)
    ->where(function ($q) {
        $q->whereNull('EPF_No')
          ->orWhereRaw("LTRIM(RTRIM(CAST(EPF_No AS VARCHAR(50)))) = ''");
    })
    ->count();

$locationCounts = $db->table('TBL_HR_MAS_EMPLOYEE as e')
    ->join(
        'TBL_HR_MAS_SUB_FUNCTION as sf',
        'e.SubFunc_Id',
        '=',
        'sf.SubFunc_Id'
    )
    ->join(
        'TBL_HR_MAS_LOCATION as l',
        'sf.Location_Id',
        '=',
        'l.Location_Id'
    )
    ->where('e.Is_Available', 1)
    ->select('l.Location_Id', 'l.Location_Name')
    ->selectRaw('COUNT(*) AS employee_count')
    ->groupBy('l.Location_Id', 'l.Location_Name')
    ->orderBy('l.Location_Id')
    ->get()
    ->map(fn ($r) => [
        'location_id' => $r->Location_Id,
        'location_name' => $r->Location_Name,
        'employee_count' => $r->employee_count,
    ])->all();

dump([
    'check' => 'Available employee identifiers',
    'duplicate_epf_group_count' => $duplicateEpfs->count(),
    'missing_epf_count' => $missingEpfs,
]);

dump([
    'check' => 'Available employees by source location',
    'locations' => $locationCounts,
]);
