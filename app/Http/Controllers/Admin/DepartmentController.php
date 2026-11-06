<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display the live CPC Department master directory.
     *
     * Source:
     * SQL Server -> CPC_HelpDesk_And_Asset_Management
     * Table      -> Department
     *
     * This directory is read-only.
     */
    public function index(Request $request)
    {
        $connection = DB::connection('sqlsrv_assets');

        $sourceQuery = $connection->table('Department');

        /*
        |--------------------------------------------------------------------------
        | Overall Statistics
        |--------------------------------------------------------------------------
        */

        $totalDepartments = (clone $sourceQuery)->count();

        $headOfficeDepartments = (clone $sourceQuery)
            ->where('LocationType', 'Head Office')
            ->count();

        $otherLocationDepartments = max(
            0,
            $totalDepartments - $headOfficeDepartments
        );

        /*
        |--------------------------------------------------------------------------
        | Available Location Types
        |--------------------------------------------------------------------------
        */

        $locationTypes = (clone $sourceQuery)
            ->whereNotNull('LocationType')
            ->where('LocationType', '<>', '')
            ->distinct()
            ->orderBy('LocationType')
            ->pluck('LocationType');

        /*
        |--------------------------------------------------------------------------
        | Search and Filters
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->query('search', ''));

        $locationType = trim(
            (string) $request->query('location_type', 'all')
        );

        $departmentsQuery = (clone $sourceQuery)
            ->select([
                'Id',
                'Name',
                'LocationCode',
                'LocationType',
            ]);

        // Search by CPC department ID, name, location code or location type.
        if ($search !== '') {
            $departmentsQuery->where(function ($query) use ($search) {

                $query->where('Name', 'like', '%' . $search . '%')
                    ->orWhere('LocationCode', 'like', '%' . $search . '%')
                    ->orWhere('LocationType', 'like', '%' . $search . '%');

                if (ctype_digit($search)) {
                    $query->orWhere('Id', (int) $search);
                }
            });
        }

        // Filter by CPC location type.
        if ($locationType !== '' && $locationType !== 'all') {
            $departmentsQuery->where('LocationType', $locationType);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $departments = $departmentsQuery
            ->orderBy('Name')
            ->orderBy('Id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.departments.index', compact(
            'departments',
            'totalDepartments',
            'headOfficeDepartments',
            'otherLocationDepartments',
            'locationTypes',
            'search',
            'locationType'
        ));
    }
}
