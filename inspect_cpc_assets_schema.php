<?php

use Illuminate\Support\Facades\DB;

$tables = ['Asset', 'Employee', 'Department', 'Ticket'];

foreach ($tables as $table) {
    try {
        $columns = DB::connection('sqlsrv_assets')
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
