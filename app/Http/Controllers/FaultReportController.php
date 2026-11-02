<?php

namespace App\Http\Controllers;

use App\Models\FaultReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class FaultReportController extends Controller
{
    /**
     * Display the logged-in user's fault reports.
     */
    public function index(Request $request): View
    {
        $reports = FaultReport::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('fault-reports.index', compact('reports'));
    }

    /**
     * Display the form for submitting a fault report.
     */
    public function create(): View
    {
        return view('fault-reports.create');
    }

    /**
     * Store a new fault report for the authenticated user.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Resolve the user's assigned branch.
        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        if ($branchId === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'branch_id' => 'Your account has no assigned branch. Please contact IT support.',
                ]);
        }

        $validated = $request->validate([
            'asset_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'device_type' => [
                'required',
                Rule::in([
                    'Laptop',
                    'Desktop PC',
                    'All-in-One PC',
                ]),
            ],
            'device_make' => [
                'nullable',
                'string',
                'max:100',
            ],
            'device_model' => [
                'nullable',
                'string',
                'max:100',
            ],
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'required',
                'string',
                'max:10000',
            ],
            'priority' => [
                'required',
                Rule::in([
                    'low',
                    'medium',
                    'high',
                    'critical',
                ]),
            ],
        ]);

        // Never accept the submitter, branch, status, or assignment
        // from browser-submitted data.
        $report = FaultReport::create([
            ...$validated,
            'user_id' => $user->id,
            'branch_id' => $branchId,
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('fault-reports.show', $report)
            ->with('success', 'Your fault report has been submitted successfully.');
    }

    /**
     * Display a report only to its submitting user.
     */
    public function show(
        Request $request,
        FaultReport $faultReport
    ): View {
        abort_unless(
            (int) $faultReport->user_id === (int) $request->user()->id,
            403
        );

        $faultReport->load([
            'user',
            'branch',
            'assignedUser',
        ]);

        return view('fault-reports.show', [
            'report' => $faultReport,
        ]);
    }
}
