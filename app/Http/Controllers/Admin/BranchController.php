<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display the live CPC branch directory.
     */
    public function index(Request $request)
    {
        $connection = DB::connection('sqlsrv');

        $sourceQuery = $connection
            ->table('TBL_HR_MAS_LOCATION');

        $totalBranches = (clone $sourceQuery)->count();

        $activeBranches = (clone $sourceQuery)
            ->where('Is_Available', 1)
            ->count();

        $inactiveBranches = (clone $sourceQuery)
            ->where(function ($query) {
                $query->where('Is_Available', '<>', 1)
                    ->orWhereNull('Is_Available');
            })
            ->count();

        $branchesQuery = (clone $sourceQuery)
            ->select([
                'Location_Id',
                'Location_Name',
                'Is_Available',
            ]);

        // Search by CPC location name or location ID.
        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $branchesQuery->where(function ($query) use ($search) {
                $query->where(
                    'Location_Name',
                    'like',
                    '%' . $search . '%'
                );

                if (ctype_digit($search)) {
                    $query->orWhere(
                        'Location_Id',
                        (int) $search
                    );
                }
            });
        }

        // Filter by CPC availability.
        $status = $request->query('status', 'all');

        if ($status === 'active') {
            $branchesQuery->where('Is_Available', 1);
        } elseif ($status === 'inactive') {
            $branchesQuery->where(function ($query) {
                $query->where('Is_Available', '<>', 1)
                    ->orWhereNull('Is_Available');
            });
        }

        $branches = $branchesQuery
            ->orderBy('Location_Id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.branches.index', compact(
            'branches',
            'totalBranches',
            'activeBranches',
            'inactiveBranches',
            'search',
            'status'
        ));
    }

    /**
     * Display details of one CPC location.
     */
    public function show($locationId)
    {
        $location = DB::connection('sqlsrv')
            ->table('TBL_HR_MAS_LOCATION')
            ->select([
                'Location_Id',
                'Location_Name',
                'Is_Available',
            ])
            ->where('Location_Id', $locationId)
            ->first();

        abort_if(!$location, 404, 'CPC location not found.');

        return view('admin.branches.show', compact('location'));
    }
}