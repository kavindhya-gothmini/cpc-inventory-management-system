<?php

use Illuminate\Support\Facades\DB;

$checks = [
    'sqlsrv' => [
        'TBL_HR_MAS_EMPLOYEE',
        'TBL_HR_MAS_DESIGNATION',
        'TBL_HR_MAS_SUB_FUNCTION',
        'TBL_HR_MAS_FUNCTION',
        'TBL_HR_MAS_LOCATION',
        'TBL_HR_MAS_UNIT',
    ],
    'sqlsrv_assets' => [
        'Asset',
        'Employee',
        'Department',
        'Ticket',
    ],
];

foreach ($checks as $connection => $tables) {
    dump(['connection' => $connection]);

    foreach ($tables as $table) {
        try {
            $columns = DB::connection($connection)
                ->getSchemaBuilder()
                ->getColumnListing($table);

            dump([
                'table' => $table,
                'columns_confirmed' => count($columns),
            ]);
        } catch (\Throwable $e) {
            dump([
                'table' => $table,
                'error' => $e->getMessage(),
            ]);
        }
    }

    $foreignKeys = DB::connection($connection)->select(
        "SELECT
            OBJECT_NAME(fkc.parent_object_id) AS source_table,
            COL_NAME(fkc.parent_object_id, fkc.parent_column_id) AS source_column,
            OBJECT_NAME(fkc.referenced_object_id) AS referenced_table,
            COL_NAME(fkc.referenced_object_id, fkc.referenced_column_id) AS referenced_column
         FROM sys.foreign_key_columns fkc
         WHERE OBJECT_NAME(fkc.parent_object_id) IN (
            'TBL_HR_MAS_EMPLOYEE',
            'TBL_HR_MAS_DESIGNATION',
            'TBL_HR_MAS_SUB_FUNCTION',
            'TBL_HR_MAS_FUNCTION',
            'TBL_HR_MAS_LOCATION',
            'TBL_HR_MAS_UNIT',
            'Asset', 'Employee', 'Department', 'Ticket'
         )
         OR OBJECT_NAME(fkc.referenced_object_id) IN (
            'TBL_HR_MAS_EMPLOYEE',
            'TBL_HR_MAS_DESIGNATION',
            'TBL_HR_MAS_SUB_FUNCTION',
            'TBL_HR_MAS_FUNCTION',
            'TBL_HR_MAS_LOCATION',
            'TBL_HR_MAS_UNIT',
            'Asset', 'Employee', 'Department', 'Ticket'
         )
         ORDER BY source_table, source_column"
    );

    dump([
        'connection' => $connection,
        'foreign_keys' => $foreignKeys,
    ]);
}
