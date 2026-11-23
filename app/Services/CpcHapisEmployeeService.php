<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CpcHapisEmployeeService
{
    /**
     * Find one currently available employee in CPC HAPIS by EPF.
     *
     * Searches the live CPC HAPIS SQL Server database first, then
     * falls back to the read-only local CPC master cache.
     *
     * Only returns fields needed for employee verification.
     */
    public function findByEpf(string $epf): ?object
    {
        $epf = trim($epf);

        if (! preg_match('/^[0-9]{5}$/', $epf)) {
            throw new \InvalidArgumentException(
                'EPF number must contain exactly five numeric digits.'
            );
        }

        try {
            $liveEmployee = DB::connection('sqlsrv')
                ->table('TBL_HR_MAS_EMPLOYEE as e')
                ->leftJoin(
                    'TBL_HR_MAS_DESIGNATION as d',
                    'e.Designation_Id',
                    '=',
                    'd.Designation_Id'
                )
                ->leftJoin(
                    'TBL_HR_MAS_SUB_FUNCTION as sf',
                    'e.SubFunc_Id',
                    '=',
                    'sf.SubFunc_Id'
                )
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
                ->where('e.EPF_No', $epf)
                ->where('e.Is_Available', 1)
                ->select([
                    'e.EPF_No as epf_no',
                    DB::raw("
                        COALESCE(
                            NULLIF(
                                LTRIM(RTRIM(e.Name_with_Initials)),
                                ''
                            ),
                            NULLIF(
                                LTRIM(RTRIM(e.Full_Name)),
                                ''
                            )
                        ) as employee_name
                    "),
                    'd.Designation_Description as designation',
                    'sf.SubFunction_Name as sub_function',
                    'f.Function_Name as function_name',
                    'l.Location_Id as source_location_id',
                    'l.Location_Name as source_location_name',
                ])
                ->first();

            if ($liveEmployee) {
                $location = DB::table('cpc_master_locations')
                    ->where('source_location_id', $liveEmployee->source_location_id)
                    ->first();

                $liveEmployee->local_branch_id =
                    $location?->local_branch_id;
                $liveEmployee->is_available = true;

                return $liveEmployee;
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        $cachedEmployee = DB::table('cpc_master_employees as e')
            ->leftJoin(
                'cpc_master_designations as d',
                'e.source_designation_id',
                '=',
                'd.source_designation_id'
            )
            ->leftJoin(
                'cpc_master_sub_functions as sf',
                'e.source_sub_function_id',
                '=',
                'sf.source_sub_function_id'
            )
            ->leftJoin(
                'cpc_master_functions as f',
                'e.source_function_id',
                '=',
                'f.source_function_id'
            )
            ->leftJoin(
                'cpc_master_locations as l',
                'e.source_location_id',
                '=',
                'l.source_location_id'
            )
            ->where(function ($query) use ($epf) {
                $query->where('e.source_epf_no', $epf)
                    ->orWhere('e.source_epf_no', 'EPF' . $epf);
            })
            ->where('e.is_available', 1)
            ->select([
                DB::raw("
                    CASE
                        WHEN LEFT(LTRIM(RTRIM(e.source_epf_no)), 3) = 'EPF'
                            THEN RIGHT(LTRIM(RTRIM(e.source_epf_no)), 5)
                        ELSE LTRIM(RTRIM(e.source_epf_no))
                    END as epf_no
                "),
                DB::raw("
                    COALESCE(
                        NULLIF(
                            LTRIM(RTRIM(e.name_with_initials)),
                            ''
                        ),
                        NULLIF(
                            LTRIM(RTRIM(e.full_name)),
                            ''
                        )
                    ) as employee_name
                "),
                'd.designation_name as designation',
                'sf.sub_function_name as sub_function',
                'f.function_name as function_name',
                'l.source_location_id',
                'l.location_name as source_location_name',
                'l.local_branch_id',
                'e.is_available',
            ])
            ->first();

        return $cachedEmployee;
    }
}
