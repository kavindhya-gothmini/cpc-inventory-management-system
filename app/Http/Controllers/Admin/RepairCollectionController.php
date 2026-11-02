<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\RepairCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairCollectionController extends Controller
{
    public function store(Request $request, Repair $repair): RedirectResponse
    {
        $validated = $request->validate([
            'collector_name' => ['required', 'string', 'max:150'],
            'collector_epf_no' => ['nullable', 'string', 'max:50'],
            'collector_contact' => ['nullable', 'string', 'max:50'],
            'issued_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $repair) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $collection = $lockedRepair->collection()
                ->lockForUpdate()
                ->first();

            if ($collection) {
                $collection->update($validated);
            } else {
                $lockedRepair->collection()->create($validated);
            }
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Collection / handover details saved successfully.');
    }

    public function destroy(Repair $repair): RedirectResponse
    {
        DB::transaction(function () use ($repair) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $collection = $lockedRepair->collection()
                ->lockForUpdate()
                ->first();

            if ($collection) {
                $collection->delete();
            }
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Collection / handover record removed successfully.');
    }

    private function lockAccessibleRepair(int $repairId): Repair
    {
        $repair = Repair::query()
            ->whereKey($repairId)
            ->lockForUpdate()
            ->firstOrFail();

        $this->ensureRepairBranchAccess($repair);

        return $repair;
    }

    private function ensureRepairBranchAccess(Repair $repair): void
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($this->isGlobalSuperAdmin($user)) {
            return;
        }

        $branchId = $this->resolveUserBranchId($user);
        abort_if($branchId === null, 403);

        $itemBranchId = $repair->item()->value('branch_id');

        abort_unless(
            $itemBranchId !== null && (int) $itemBranchId === $branchId,
            404
        );
    }

    private function resolveUserBranchId($user): ?int
    {
        $branchId = $user->branch_id ?? $user->employee?->branch_id;

        return $branchId === null ? null : (int) $branchId;
    }

    private function isGlobalSuperAdmin($user): bool
    {
        return strtolower((string) $user->role) === 'super_admin'
            && $this->resolveUserBranchId($user) === null;
    }
}
