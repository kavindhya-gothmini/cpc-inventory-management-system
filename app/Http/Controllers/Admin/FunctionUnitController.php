<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\FunctionUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunctionUnitController extends Controller
{
    /**
     * Display CPC Function and Sub-function master data.
     * Source: CPC HR SQL Server database.
     */
    public function index(Request $request)
    {
        $connection = DB::connection('sqlsrv');

        /*
        |--------------------------------------------------------------------------
        | FILTER INPUTS
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->query('search', ''));

        $functionId = trim((string) $request->query('function_id', ''));

        $subFunctionSearch = trim(
            (string) $request->query('sub_function', '')
        );

        $locationId = trim(
            (string) $request->query('location_id', '')
        );

        $costCentre = trim(
            (string) $request->query('cost_centre', '')
        );

        $ibmCode = trim(
            (string) $request->query('ibm_code', '')
        );

        $status = $request->query('status', 'all');

        if (!in_array($status, ['all', 'active', 'inactive'], true)) {
            $status = 'all';
        }

        /*
        |--------------------------------------------------------------------------
        | FUNCTION DROPDOWN
        |--------------------------------------------------------------------------
        | Load actual CPC Function master records.
        | This is the data required by the Blade dropdown.
        */

        $functionOptions = $connection
            ->table('TBL_HR_MAS_FUNCTION')
            ->select([
                'Function_Id as function_id',
                'Function_Name as function_name',
            ])
            ->orderBy('Function_Name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CPC MASTER DATA SUMMARY
        |--------------------------------------------------------------------------
        */

        $functionsCount = $connection
            ->table('TBL_HR_MAS_FUNCTION')
            ->count();

        $subFunctionsCount = $connection
            ->table('TBL_HR_MAS_SUB_FUNCTION')
            ->count();

        $unitsCount = $connection
            ->table('TBL_HR_MAS_UNIT')
            ->count();

        $activeFunctionsCount = $connection
            ->table('TBL_HR_MAS_FUNCTION')
            ->where('Is_Available', '1')
            ->count();

        $activeSubFunctionsCount = $connection
            ->table('TBL_HR_MAS_SUB_FUNCTION')
            ->where('Is_Available', '1')
            ->count();

        $inactiveSubFunctionsCount = $connection
            ->table('TBL_HR_MAS_SUB_FUNCTION')
            ->where('Is_Available', '0')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | FUNCTION AND SUB-FUNCTION DIRECTORY QUERY
        |--------------------------------------------------------------------------
        */

        $query = $connection
            ->table('TBL_HR_MAS_SUB_FUNCTION as s')
            ->join(
                'TBL_HR_MAS_FUNCTION as f',
                's.Function_Id',
                '=',
                'f.Function_Id'
            )
            ->select([
                'f.Function_Id as function_id',
                'f.Function_Name as function_name',
                'f.IBM_Function_Code as function_ibm_code',
                'f.Is_Available as function_is_available',

                's.SubFunc_Id as sub_function_id',
                's.SubFunction_Name as sub_function_name',
                's.Location_Id as location_id',
                's.Cost_Centre as cost_centre',
                's.Fund as fund',
                's.IBM_Code as sub_function_ibm_code',
                's.Is_Available as sub_function_is_available',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 1. GENERAL SEARCH
        |--------------------------------------------------------------------------
        | Searches Function, Sub-function, IDs, Location,
        | Cost Centre and IBM Code.
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('f.Function_Name', 'like', "%{$search}%")
                    ->orWhere('f.Function_Id', 'like', "%{$search}%")
                    ->orWhere('s.SubFunction_Name', 'like', "%{$search}%")
                    ->orWhere('s.SubFunc_Id', 'like', "%{$search}%")
                    ->orWhere('s.Location_Id', 'like', "%{$search}%")
                    ->orWhere('s.Cost_Centre', 'like', "%{$search}%")
                    ->orWhere('s.IBM_Code', 'like', "%{$search}%");

            });

        }

        /*
        |--------------------------------------------------------------------------
        | 2. FUNCTION FILTER
        |--------------------------------------------------------------------------
        | Displays Sub-functions belonging to the selected Function.
        */

        if ($functionId !== '') {

            $query->where(
                's.Function_Id',
                '=',
                $functionId
            );

        }

        /*
        |--------------------------------------------------------------------------
        | 3. SUB-FUNCTION FILTER
        |--------------------------------------------------------------------------
        | Search by Sub-function name or ID.
        */

        if ($subFunctionSearch !== '') {

            $query->where(function ($q) use ($subFunctionSearch) {

                $q->where(
                    's.SubFunction_Name',
                    'like',
                    "%{$subFunctionSearch}%"
                )
                ->orWhere(
                    's.SubFunc_Id',
                    'like',
                    "%{$subFunctionSearch}%"
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | 4. LOCATION ID FILTER
        |--------------------------------------------------------------------------
        */

        if ($locationId !== '') {

            $query->where(
                's.Location_Id',
                'like',
                "%{$locationId}%"
            );

        }

        /*
        |--------------------------------------------------------------------------
        | 5. COST CENTRE FILTER
        |--------------------------------------------------------------------------
        */

        if ($costCentre !== '') {

            $query->where(
                's.Cost_Centre',
                'like',
                "%{$costCentre}%"
            );

        }

        /*
        |--------------------------------------------------------------------------
        | 6. IBM CODE FILTER
        |--------------------------------------------------------------------------
        */

        if ($ibmCode !== '') {

            $query->where(
                's.IBM_Code',
                'like',
                "%{$ibmCode}%"
            );

        }

        /*
        |--------------------------------------------------------------------------
        | 7. STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($status === 'active') {

            $query->where('s.Is_Available', '1');

        } elseif ($status === 'inactive') {

            $query->where('s.Is_Available', '0');

        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        | Keep all selected filters when moving between pages.
        */

        $subFunctions = $query
            ->orderBy('f.Function_Name')
            ->orderBy('s.SubFunction_Name')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.function-units.index', compact(

            'functionsCount',
            'subFunctionsCount',
            'unitsCount',

            'activeFunctionsCount',
            'activeSubFunctionsCount',
            'inactiveSubFunctionsCount',

            'subFunctions',
            'functionOptions',

            'search',
            'functionId',
            'subFunctionSearch',
            'locationId',
            'costCentre',
            'ibmCode',
            'status'

        ));
    }

    /**
     * Existing local MySQL Function Unit CRUD methods.
     * These are retained to preserve application compatibility.
     */

    public function create()
    {
        $this->ensureSuperAdmin();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.function-units.create',
            compact('departments')
        );
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:100',
                'unique:function_units,code'
            ],
            'department_id' => [
                'required',
                'exists:departments,id'
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        FunctionUnit::create($validated);

        return redirect()
            ->route('admin.function-units.index')
            ->with('success', 'Function Unit created successfully.');
    }

    public function show(FunctionUnit $functionUnit)
    {
        $functionUnit->load([
            'department',
            'employees',
            'items',
        ]);

        return view(
            'admin.function-units.show',
            compact('functionUnit')
        );
    }

    public function edit(FunctionUnit $functionUnit)
    {
        $this->ensureSuperAdmin();

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.function-units.edit',
            compact('functionUnit', 'departments')
        );
    }

    public function update(
        Request $request,
        FunctionUnit $functionUnit
    ) {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:100',
                'unique:function_units,code,' . $functionUnit->id,
            ],
            'department_id' => [
                'required',
                'exists:departments,id'
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $functionUnit->update($validated);

        return redirect()
            ->route('admin.function-units.index')
            ->with('success', 'Function Unit updated successfully.');
    }

    public function destroy(FunctionUnit $functionUnit)
    {
        $this->ensureSuperAdmin();

        $functionUnit->update([
            'is_active' => false
        ]);

        return redirect()
            ->route('admin.function-units.index')
            ->with('success', 'Function Unit deactivated successfully.');
    }

    /**
     * Super Admin authorization.
     */
    private function ensureSuperAdmin(): void
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403, 'Only Super Admin can manage function units.');
        }
    }
}
