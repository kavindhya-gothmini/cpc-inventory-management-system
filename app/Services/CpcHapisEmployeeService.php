<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CpcHapisEmployeeService
{
    /**
     * Find one currently available employee in CPC HAPIS by EPF.
     *
     * Read-only: this service only executes a SELECT query against
     * the CPC HAPIS SQL Server connection.
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

        return DB::connection('sqlsrv')
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
    }
}
