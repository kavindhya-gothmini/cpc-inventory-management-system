<?php

namespace App\Http\Controllers;

use App\Models\FaultReport;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FaultReportController extends Controller
{
    public function adminIndex(Request $request): View
    {
        $this->ensureFaultReportAdmin($request->user());

        $branchId = $this->resolveBranchId($request->user());
        $query = FaultReport::query()->with([
            'user',
            'branch',
            'assignedUser',
            'assignedEmployee',
        ]);

        if (! $this->isFaultReportGlobal($request->user())) {
            $query->where('branch_id', $branchId);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('fault-reports.admin-index', compact('reports'));
    }

    /**
     * Display the logged-in user's fault reports.
     */
    public function index(Request $request): View
    {
        abort_unless(
            strtolower((string) $request->user()->role) === 'employee',
            403
        );

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
        $isAdmin = $this->isFaultReportAdmin($request->user());

        if (! $isAdmin) {
            abort_unless(
                (int) $faultReport->user_id === (int) $request->user()->id,
                403
            );
        } elseif (! $this->isFaultReportGlobal($request->user())) {
            abort_unless(
                (int) $faultReport->branch_id
                    === (int) $this->resolveBranchId($request->user()),
                403
            );
        }

        $faultReport->load([
            'user',
            'branch',
            'assignedUser',
            'assignedEmployee',
        ]);

        $assignedEmployees = collect();

        if ($isAdmin) {
            $assignedEmployees = DB::table('cpc_master_employees as c')
                ->leftJoin(
                    'cpc_master_locations as l',
                    'l.source_location_id',
                    '=',
                    'c.source_location_id'
                )
                ->leftJoin(
                    'employees as e',
                    'e.epf_no',
                    '=',
                    'c.source_epf_no'
                )
                ->where('c.source_function_id', 105)
                ->where('c.is_available', 1)
                ->select([
                    'c.source_epf_no as epf_no',
                    'c.name_with_initials',
                    'c.full_name',
                    'e.id as employee_id',
                    'l.local_branch_id',
                ])
                ->orderByRaw(
                    'COALESCE(c.name_with_initials, c.full_name)'
                )
                ->get();
        }

        return view('fault-reports.show', [
            'report' => $faultReport,
            'isFaultReportAdmin' => $isAdmin,
            'assignedEmployees' => $assignedEmployees,
        ]);
    }

    public function update(Request $request, FaultReport $faultReport): RedirectResponse
    {
        $this->ensureFaultReportAdmin($request->user());

        if (! $this->isFaultReportGlobal($request->user())) {
            abort_unless(
                (int) $faultReport->branch_id
                    === (int) $this->resolveBranchId($request->user()),
                403
            );
        }

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'submitted',
                    'under_review',
                    'in_progress',
                    'waiting_for_user',
                    'resolved',
                    'closed',
                ]),
            ],
            'assigned_employee_epf' => [
                'nullable',
                'string',
                'regex:/^(?:EPF)?[0-9]{5}$/',
            ],
            'it_response' => ['nullable', 'string', 'max:10000'],
            'resolution_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $assignedEmployeeId = null;

        if (! empty($validated['assigned_employee_epf'])) {
            $rawEpf = strtoupper(trim($validated['assigned_employee_epf']));
            $epf = preg_replace('/^EPF/', '', $rawEpf);

            $cpcEmployee = DB::table('cpc_master_employees as c')
                ->leftJoin(
                    'cpc_master_designations as d',
                    'd.source_designation_id',
                    '=',
                    'c.source_designation_id'
                )
                ->leftJoin(
                    'cpc_master_locations as l',
                    'l.source_location_id',
                    '=',
                    'c.source_location_id'
                )
                ->where('c.source_function_id', 105)
                ->where('c.is_available', 1)
                ->where(function ($query) use ($epf) {
                    $query->where('c.source_epf_no', $epf)
                        ->orWhere('c.source_epf_no', 'EPF' . $epf);
                })
                ->select([
                    'c.source_epf_no',
                    'c.name_with_initials',
                    'c.full_name',
                    'd.designation_name',
                    'l.local_branch_id',
                ])
                ->first();

            abort_unless(
                $cpcEmployee,
                422,
                'The assigned employee must belong to CPC IT function 105.'
            );

            $employee = Employee::whereIn('epf_no', [
                $epf,
                'EPF' . $epf,
            ])
                ->first();

            if (! $employee) {
                $employee = Employee::create([
                    'epf_no' => $epf,
                    'name' => trim(
                        (string) (
                            $cpcEmployee->name_with_initials
                            ?: $cpcEmployee->full_name
                        )
                    ),
                    'designation' => $cpcEmployee->designation_name,
                    'branch_id' => $cpcEmployee->local_branch_id,
                    'department_id' => null,
                    'function_unit_id' => null,
                    'is_active' => true,
                ]);
            }

            $assignedEmployeeId = $employee->id;
        }

        unset($validated['assigned_employee_epf']);
        $validated['assigned_employee_id'] = $assignedEmployeeId;
        $validated['assigned_to'] = null;

        $resolvedStatuses = ['resolved', 'closed'];
        $validated['resolved_at'] =
            in_array($validated['status'], $resolvedStatuses, true)
                ? now()
                : null;

        $faultReport->update($validated);

        return redirect()
            ->route('fault-reports.show', $faultReport)
            ->with('success', 'Fault report updated successfully.');
    }

    private function ensureFaultReportAdmin(User $user): void
    {
        abort_unless(
            $this->isFaultReportAdmin($user),
            403
        );
    }

    private function isFaultReportAdmin(User $user): bool
    {
        return in_array(
            strtolower(trim((string) $user->role)),
            ['admin', 'super_admin'],
            true
        );
    }

    private function isGlobalSuperAdmin(User $user): bool
    {
        return strtolower(trim((string) $user->role)) === 'super_admin'
            && $this->resolveBranchId($user) === null;
    }

    private function getFunction105Epfs(): array
    {
        $normalized = DB::table('cpc_master_employees')
            ->where('source_function_id', 105)
            ->where('is_available', 1)
            ->whereNotNull('source_epf_no')
            ->pluck('source_epf_no')
            ->map(function ($epf) {
                $epf = strtoupper(trim((string) $epf));

                if (preg_match('/^EPF([0-9]{5})$/', $epf, $matches)) {
                    return $matches[1];
                }

                return preg_match('/^[0-9]{5}$/', $epf)
                    ? $epf
                    : null;
            })
            ->filter()
            ->unique()
            ->values();

        return $normalized
            ->flatMap(fn ($epf) => [$epf, 'EPF' . $epf])
            ->unique()
            ->values()
            ->all();
    }

    private function isFaultReportGlobal(User $user): bool
    {
        $role = strtolower(trim((string) $user->role));

        if ($role === 'super_admin') {
            return true;
        }

        return $role === 'admin'
            && $this->resolveBranchId($user) === null;
    }

    private function resolveBranchId(User $user): ?int
    {
        $branchId = $user->branch_id
            ?? $user->employee?->branch_id;

        return $branchId === null ? null : (int) $branchId;
    }
}
