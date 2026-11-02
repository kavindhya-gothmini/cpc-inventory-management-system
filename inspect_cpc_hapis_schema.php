<?php

use Illuminate\Support\Facades\DB;

$tables = [
    'TBL_HR_MAS_EMPLOYEE',
    'TBL_HR_MAS_DESIGNATION',
    'TBL_HR_MAS_UNIT',
    'TBL_HR_MAS_FUNCTION',
    'TBL_HR_MAS_SUB_FUNCTION',
    'TBL_HR_MAS_LOCATION',
];

foreach ($tables as $table) {
    try {
        $columns = DB::connection('sqlsrv')
            ->getSchemaBuilder()
            ->getColumnListing($table);

        dump([
            'table' => $table,
            'columns' => $columns,
        ]);
    } catch (\Throwable $e) {
        dump([
            'table' => $table,
            'error' => $e->getMessage(),
        ]);
    }
}
