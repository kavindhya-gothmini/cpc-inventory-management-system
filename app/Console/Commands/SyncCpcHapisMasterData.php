<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class SyncCpcHapisMasterData extends Command
{
    protected $signature = 'cpc:sync-hapis-master
                            {--dry-run : Read and validate source data without writing to MySQL}';

    protected $description =
        'Synchronise CPC HAPIS reference data into local master cache tables';

    private string $sourceConnection = 'sqlsrv';

    public function handle(): int
    {
        try {
            $source = DB::connection($this->sourceConnection);

            // Validate the source before reading or writing.
            $tables = [
                'TBL_HR_MAS_LOCATION',
                'TBL_HR_MAS_FUNCTION',
                'TBL_HR_MAS_SUB_FUNCTION',
                'TBL_HR_MAS_DESIGNATION',
                'TBL_HR_MAS_EMPLOYEE',
            ];

            foreach ($tables as $table) {
                if (! $source->getSchemaBuilder()->hasTable($table)) {
                    throw new RuntimeException(
                        "Required HAPIS source table not found: {$table}"
                    );
                }
            }

            $locations = $source->table('TBL_HR_MAS_LOCATION')->get();
            $functions = $source->table('TBL_HR_MAS_FUNCTION')->get();
            $subFunctions = $source->table('TBL_HR_MAS_SUB_FUNCTION')->get();
            $designations = $source->table('TBL_HR_MAS_DESIGNATION')->get();
            $employees = $source->table('TBL_HR_MAS_EMPLOYEE')->get();

            $this->info('Source read completed:');
            $this->line('Locations: ' . $locations->count());
            $this->line('Functions: ' . $functions->count());
            $this->line('Sub-functions: ' . $subFunctions->count());
            $this->line('Designations: ' . $designations->count());
            $this->line('Employees: ' . $employees->count());

            $this->validateUniqueKeys($locations, 'Location_Id', 'locations');
            $this->validateUniqueKeys($functions, 'Function_Id', 'functions');
            $this->validateUniqueKeys(
                $subFunctions,
                'SubFunc_Id',
                'sub-functions'
            );
            $this->validateUniqueKeys(
                $designations,
                'Designation_Id',
                'designations'
            );
            $this->validateUniqueKeys($employees, 'EPF_No', 'employees');

            if ($this->option('dry-run')) {
                $this->info(
                    'Dry run complete. No local MySQL data was changed.'
                );

                return self::SUCCESS;
            }

            $this->assertCacheTablesExist();

            // Explicit source-location-ID to local branch CODE mapping.
            // The mapping uses branch code, not the local branch primary key.
            $branchCodeBySourceLocation = [
                1000 => '1000',
                2000 => '2000',
                2001 => '2001',
                2002 => '2002',
                2003 => '2003',
                2004 => '2004',
                2005 => '2005',
                2006 => '2006',
                2007 => '2007',
                2008 => '2008',
                2009 => '2009',
            ];

            $localBranches = DB::table('branches')
                ->whereIn('code', array_values($branchCodeBySourceLocation))
                ->pluck('id', 'code');

            $locationToBranchId = [];

            foreach ($branchCodeBySourceLocation as $sourceId => $branchCode) {
                if (isset($localBranches[$branchCode])) {
                    $locationToBranchId[$sourceId] = $localBranches[$branchCode];
                }
            }

            $now = now();

            DB::transaction(function () use (
                $locations,
                $functions,
                $subFunctions,
                $designations,
                $employees,
                $locationToBranchId,
                $now
            ): void {
                $this->upsertRows(
                    'cpc_master_locations',
                    $locations->map(function ($row) use (
                        $locationToBranchId,
                        $now
                    ) {
                        $r = (array) $row;
                        $sourceId = (int) $r['Location_Id'];

                        return [
                            'source_location_id' => $sourceId,
                            'location_name' => $r['Location_Name'] ?? null,
                            'is_available' => $this->nullableBool(
                                $r['Is_Available'] ?? null
                            ),
                            'ibm_location' => $r['IBM_Location'] ?? null,
                            'local_branch_id' => $locationToBranchId[$sourceId] ?? null,
                            'source_payload' => $this->json($r),
                            'source_synced_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all(),
                    ['source_location_id'],
                    [
                        'location_name', 'is_available', 'ibm_location',
                        'local_branch_id', 'source_payload', 'source_synced_at',
                        'updated_at',
                    ]
                );

                $this->upsertRows(
                    'cpc_master_functions',
                    $functions->map(function ($row) use ($now) {
                        $r = (array) $row;

                        return [
                            'source_function_id' => (int) $r['Function_Id'],
                            'function_name' => $r['Function_Name'] ?? null,
                            'is_available' => $this->nullableBool(
                                $r['Is_Available'] ?? null
                            ),
                            'source_payload' => $this->json($r),
                            'source_synced_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all(),
                    ['source_function_id'],
                    [
                        'function_name', 'is_available', 'source_payload',
                        'source_synced_at', 'updated_at',
                    ]
                );

                $this->upsertRows(
                    'cpc_master_sub_functions',
                    $subFunctions->map(function ($row) use ($now) {
                        $r = (array) $row;

                        return [
                            'source_sub_function_id' => (int) $r['SubFunc_Id'],
                            'sub_function_name' => $r['SubFunction_Name'] ?? null,
                            'source_function_id' => isset($r['Function_Id'])
                                ? (int) $r['Function_Id'] : null,
                            'source_location_id' => isset($r['Location_Id'])
                                ? (int) $r['Location_Id'] : null,
                            'cost_centre' => $r['Cost_Centre'] ?? null,
                            'fund' => $r['Fund'] ?? null,
                            'ibm_code' => $r['IBM_Code'] ?? null,
                            'is_available' => $this->nullableBool(
                                $r['Is_Available'] ?? null
                            ),
                            'source_payload' => $this->json($r),
                            'source_synced_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all(),
                    ['source_sub_function_id'],
                    [
                        'sub_function_name', 'source_function_id',
                        'source_location_id', 'cost_centre', 'fund',
                        'ibm_code', 'is_available', 'source_payload',
                        'source_synced_at', 'updated_at',
                    ]
                );

                $this->upsertRows(
                    'cpc_master_designations',
                    $designations->map(function ($row) use ($now) {
                        $r = (array) $row;

                        return [
                            'source_designation_id' =>
                                (string) $r['Designation_Id'],
                            'designation_name' =>
                                $r['Designation_Description'] ?? null,
                            'is_available' => $this->nullableBool(
                                $r['Designation_Is_Active'] ?? null
                            ),
                            'source_payload' => $this->json($r),
                            'source_synced_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all(),
                    ['source_designation_id'],
                    [
                        'designation_name', 'is_available', 'source_payload',
                        'source_synced_at', 'updated_at',
                    ]
                );

                $subFunctionById = $subFunctions->keyBy(
                    fn ($row) => (string) $row->SubFunc_Id
                );

                $this->upsertRows(
                    'cpc_master_employees',
                    $employees->map(function ($row) use (
                        $subFunctionById,
                        $now
                    ) {
                        $r = (array) $row;
                        $sub = $subFunctionById->get(
                            (string) ($r['SubFunc_Id'] ?? '')
                        );

                        return [
                            'source_epf_no' => trim((string) $r['EPF_No']),
                            'name_with_initials' =>
                                $r['Name_with_Initials'] ?? null,
                            'full_name' => $r['Full_Name'] ?? null,
                            'is_available' => $this->nullableBool(
                                $r['Is_Available'] ?? null
                            ),
                            'source_sub_function_id' =>
                                isset($r['SubFunc_Id'])
                                    ? (int) $r['SubFunc_Id'] : null,
                            'source_designation_id' =>
                                isset($r['Designation_Id'])
                                    ? (string) $r['Designation_Id'] : null,
                            'source_location_id' =>
                                isset($sub->Location_Id)
                                    ? (int) $sub->Location_Id : null,
                            'source_function_id' =>
                                isset($sub->Function_Id)
                                    ? (int) $sub->Function_Id : null,
                            'source_payload' => $this->json($r),
                            'source_synced_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })->all(),
                    ['source_epf_no'],
                    [
                        'name_with_initials', 'full_name', 'is_available',
                        'source_sub_function_id', 'source_designation_id',
                        'source_location_id', 'source_function_id',
                        'source_payload', 'source_synced_at', 'updated_at',
                    ]
                );
            });

            $this->info('HAPIS master cache synchronisation completed.');

            foreach ([
                'cpc_master_locations',
                'cpc_master_functions',
                'cpc_master_sub_functions',
                'cpc_master_designations',
                'cpc_master_employees',
            ] as $table) {
                $this->line($table . ': ' . DB::table($table)->count());
            }

            $unmapped = DB::table('cpc_master_locations')
                ->whereNull('local_branch_id')
                ->count();

            $this->warn(
                "Source locations without a local branch mapping: {$unmapped}. "
                . 'They remain cached and are not assigned to an incorrect branch.'
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            report($e);
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function assertCacheTablesExist(): void
    {
        foreach ([
            'cpc_master_locations',
            'cpc_master_functions',
            'cpc_master_sub_functions',
            'cpc_master_designations',
            'cpc_master_employees',
        ] as $table) {
            if (! Schema::connection('mysql')->hasTable($table)) {
                throw new RuntimeException(
                    "Missing local cache table {$table}. Run the migration first."
                );
            }
        }

        if (! Schema::connection('mysql')->hasTable('branches')
            || ! Schema::connection('mysql')->hasColumn('branches', 'code')) {
            throw new RuntimeException(
                'The local branches table or its code column is missing.'
            );
        }
    }

    private function validateUniqueKeys(
        $rows,
        string $key,
        string $label
    ): void {
        $values = $rows->map(
            fn ($row) => trim((string) ($row->{$key} ?? ''))
        );

        if ($values->contains('')) {
            throw new RuntimeException(
                "Blank {$key} found in source {$label}."
            );
        }

        if ($values->count() !== $values->unique()->count()) {
            throw new RuntimeException(
                "Duplicate {$key} values found in source {$label}; "
                . 'stopping before local writes.'
            );
        }
    }

    private function upsertRows(
        string $table,
        array $rows,
        array $uniqueBy,
        array $update
    ): void {
        foreach (array_chunk($rows, 250) as $chunk) {
            if ($chunk !== []) {
                DB::table($table)->upsert($chunk, $uniqueBy, $update);
            }
        }
    }

    private function nullableBool(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (bool) $value;
    }

    private function json(array $row): string
    {
        return json_encode(
            $row,
            JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}
