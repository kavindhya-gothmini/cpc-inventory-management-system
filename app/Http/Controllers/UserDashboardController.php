<?php

namespace App\Http\Controllers;

use App\Models\FaultReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Resolve the employee's branch from their account or employee record.
        $branch = $user->branch ?? $user->employee?->branch;

        // Employees can only access fault reports submitted by their own account.
        $myReportsQuery = FaultReport::query()
            ->where('user_id', $user->id);

        $totalReports = (clone $myReportsQuery)->count();

        $submittedReports = (clone $myReportsQuery)
            ->where('status', 'submitted')
            ->count();

        $inProgressReports = (clone $myReportsQuery)
            ->whereIn('status', [
                'under_review',
                'in_progress',
                'waiting_for_user',
            ])
            ->count();

        $resolvedReports = (clone $myReportsQuery)
            ->whereIn('status', ['resolved', 'closed'])
            ->count();

        $recentReports = (clone $myReportsQuery)
            ->with(['branch', 'assignedUser'])
            ->latest()
            ->take(5)
            ->get();

        $branchName = $branch?->name ?? 'Branch not assigned';

        return view('user-dashboard', compact(
            'user',
            'branchName',
            'totalReports',
            'submittedReports',
            'inProgressReports',
            'resolvedReports',
            'recentReports'
        ));
    }
}
