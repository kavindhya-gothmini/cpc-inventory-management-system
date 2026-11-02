<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Repair;
use App\Models\RepairAccessory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairAccessoryController extends Controller
{
    public function store(Request $request, Repair $repair): RedirectResponse
    {
        $validated = $request->validate([
            'accessory_name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1'],
            'issued_date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($repair, $validated) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $lockedRepair->accessories()->create([
                'accessory_name' => $validated['accessory_name'],
                'quantity' => $validated['quantity'],
                'issued_date' => $validated['issued_date'],
                'returned' => false,
                'returned_date' => null,
                'returned_by' => null,
                'return_remarks' => null,
            ]);
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Accessory added to the repair successfully.');
    }

    public function update(
        Request $request,
        Repair $repair,
        RepairAccessory $accessory
    ): RedirectResponse {
        $validated = $request->validate([
            'accessory_name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1'],
            'issued_date' => ['required', 'date'],
        ]);

        $result = DB::transaction(function () use (
            $repair,
            $accessory,
            $validated
        ) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);
            $lockedAccessory = $this->lockAccessory(
                $lockedRepair,
                $accessory->id
            );

            if ($lockedAccessory->returned) {
                return false;
            }

            $lockedAccessory->update([
                'accessory_name' => $validated['accessory_name'],
                'quantity' => $validated['quantity'],
                'issued_date' => $validated['issued_date'],
            ]);

            return true;
        });

        if (! $result) {
            return redirect()
                ->route('admin.inventory.repairs.show', $repair)
                ->with('error', 'A returned accessory cannot be edited.');
        }

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Accessory updated successfully.');
    }

    public function returnAccessory(
        Request $request,
        Repair $repair,
        RepairAccessory $accessory
    ): RedirectResponse {
        $validated = $request->validate([
            'returned_date' => ['required', 'date'],
            'returned_by' => ['required', 'string', 'max:150'],
            'return_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $accessoryName = DB::transaction(function () use (
            $repair,
            $accessory,
            $validated
        ) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);
            $lockedAccessory = $this->lockAccessory(
                $lockedRepair,
                $accessory->id
            );

            if ($lockedAccessory->returned) {
                return null;
            }

            $lockedAccessory->update([
                'returned' => true,
                'returned_date' => $validated['returned_date'],
                'returned_by' => $validated['returned_by'],
                'return_remarks' => $validated['return_remarks'] ?? null,
            ]);

            return $lockedAccessory->accessory_name;
        });

        if ($accessoryName === null) {
            return redirect()
                ->route('admin.inventory.repairs.show', $repair)
                ->with('error', 'This accessory has already been marked as returned.');
        }

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with(
                'success',
                $accessoryName . ' has been recorded as returned successfully.'
            );
    }

    public function destroy(
        Repair $repair,
        RepairAccessory $accessory
    ): RedirectResponse {
        $deleted = DB::transaction(function () use ($repair, $accessory) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);
            $lockedAccessory = $this->lockAccessory(
                $lockedRepair,
                $accessory->id
            );

            if ($lockedAccessory->returned) {
                return false;
            }

            $lockedAccessory->delete();

            return true;
        });

        if (! $deleted) {
            return redirect()
                ->route('admin.inventory.repairs.show', $repair)
                ->with('error', 'A returned accessory cannot be deleted.');
        }

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Accessory removed from the repair successfully.');
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

    private function lockAccessory(
        Repair $repair,
        int $accessoryId
    ): RepairAccessory {
        return $repair->accessories()
            ->whereKey($accessoryId)
            ->lockForUpdate()
            ->firstOrFail();
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
