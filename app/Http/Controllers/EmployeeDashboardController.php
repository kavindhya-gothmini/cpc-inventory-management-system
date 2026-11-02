<?php

namespace App\Http\Controllers;

use App\Models\FaultReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Only retrieve reports belonging to the authenticated user.
        $reportsQuery = FaultReport::query()
            ->where('user_id', $user->id);

        $totalReports = (clone $reportsQuery)->count();

        $submittedReports = (clone $reportsQuery)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        $inProgressReports = (clone $reportsQuery)
            ->whereIn('status', [
                'in_progress',
                'waiting_for_user',
            ])
            ->count();

        $resolvedReports = (clone $reportsQuery)
            ->whereIn('status', ['resolved', 'closed'])
            ->count();

        // Display the five most recent reports.
        $faultReports = (clone $reportsQuery)
            ->with(['branch', 'assignedUser'])
            ->latest()
            ->paginate(5);

        $employee = $user->employee;

        $branch = $user->branch ?? $employee?->branch;

        return view('employee-dashboard', [
            'user' => $user,
            'employee' => $employee,
            'branch' => $branch,
            'totalReports' => $totalReports,
            'submittedReports' => $submittedReports,
            'inProgressReports' => $inProgressReports,
            'resolvedReports' => $resolvedReports,
            'faultReports' => $faultReports,
        ]);
    }
}
