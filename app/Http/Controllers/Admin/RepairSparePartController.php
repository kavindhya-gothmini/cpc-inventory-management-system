<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\RepairSparePart;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairSparePartController extends Controller
{
    public function store(
        Request $request,
        Repair $repair
    ): RedirectResponse {
        $validated = $request->validate([
            'spare_part_id' => ['required', 'integer', 'exists:spare_parts,id'],
            'quantity_used' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $repair) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $sparePart = SparePart::query()
                ->whereKey($validated['spare_part_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($sparePart->status !== 'active') {
                abort(422, 'The selected spare part is inactive and cannot be used.');
            }

            $quantityUsed = (int) $validated['quantity_used'];
            $quantityBefore = (int) $sparePart->quantity;

            if ($quantityUsed > $quantityBefore) {
                abort(
                    422,
                    'Insufficient stock. Available stock: ' . $quantityBefore . '.'
                );
            }

            $unitPrice = (float) $sparePart->unit_price;
            $quantityAfter = $quantityBefore - $quantityUsed;

            $lockedRepair->repairSpareParts()->create([
                'spare_part_id' => $sparePart->id,
                'quantity_used' => $quantityUsed,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $quantityUsed,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $sparePart->update(['quantity' => $quantityAfter]);

            $sparePart->stockMovements()->create([
                'movement_type' => 'used_for_repair',
                'quantity' => $quantityUsed,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'repair_id' => $lockedRepair->id,
                'reference' => $lockedRepair->repair_reference,
                'remarks' => 'Spare part used for repair '
                    . $lockedRepair->repair_reference . '.',
            ]);
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Spare part added to repair and stock deducted successfully.');
    }

    public function update(
        Request $request,
        Repair $repair,
        RepairSparePart $repairSparePart
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity_used' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use (
            $validated,
            $repair,
            $repairSparePart
        ) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $usage = $lockedRepair->repairSpareParts()
                ->whereKey($repairSparePart->id)
                ->lockForUpdate()
                ->firstOrFail();

            $sparePart = SparePart::query()
                ->whereKey($usage->spare_part_id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldQuantity = (int) $usage->quantity_used;
            $newQuantity = (int) $validated['quantity_used'];
            $quantityBeforeRestore = (int) $sparePart->quantity;
            $restoredQuantity = $quantityBeforeRestore + $oldQuantity;

            if ($newQuantity > $restoredQuantity) {
                abort(
                    422,
                    'Insufficient stock for the new quantity. Available stock: '
                    . $restoredQuantity . '.'
                );
            }

            $sparePart->update(['quantity' => $restoredQuantity]);

            $sparePart->stockMovements()->create([
                'movement_type' => 'return',
                'quantity' => $oldQuantity,
                'quantity_before' => $quantityBeforeRestore,
                'quantity_after' => $restoredQuantity,
                'repair_id' => $lockedRepair->id,
                'reference' => $lockedRepair->repair_reference,
                'remarks' => 'Previous spare-part usage restored before quantity update.',
            ]);

            $unitPrice = (float) $sparePart->unit_price;
            $quantityAfterDeduction = $restoredQuantity - $newQuantity;

            $usage->update([
                'quantity_used' => $newQuantity,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $newQuantity,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $sparePart->update(['quantity' => $quantityAfterDeduction]);

            $sparePart->stockMovements()->create([
                'movement_type' => 'used_for_repair',
                'quantity' => $newQuantity,
                'quantity_before' => $restoredQuantity,
                'quantity_after' => $quantityAfterDeduction,
                'repair_id' => $lockedRepair->id,
                'reference' => $lockedRepair->repair_reference,
                'remarks' => 'Updated spare-part usage for repair '
                    . $lockedRepair->repair_reference . '.',
            ]);
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Spare-part usage updated and stock adjusted successfully.');
    }

    public function destroy(
        Repair $repair,
        RepairSparePart $repairSparePart
    ): RedirectResponse {
        DB::transaction(function () use ($repair, $repairSparePart) {
            $lockedRepair = $this->lockAccessibleRepair($repair->id);

            $usage = $lockedRepair->repairSpareParts()
                ->whereKey($repairSparePart->id)
                ->lockForUpdate()
                ->firstOrFail();

            $sparePart = SparePart::query()
                ->whereKey($usage->spare_part_id)
                ->lockForUpdate()
                ->firstOrFail();

            $quantityReturned = (int) $usage->quantity_used;
            $quantityBefore = (int) $sparePart->quantity;
            $quantityAfter = $quantityBefore + $quantityReturned;

            $sparePart->update(['quantity' => $quantityAfter]);

            $sparePart->stockMovements()->create([
                'movement_type' => 'return',
                'quantity' => $quantityReturned,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'repair_id' => $lockedRepair->id,
                'reference' => $lockedRepair->repair_reference,
                'remarks' => 'Spare part removed from repair and returned to stock.',
            ]);

            $usage->delete();
        });

        return redirect()
            ->route('admin.inventory.repairs.show', $repair)
            ->with('success', 'Spare part removed from repair and stock restored successfully.');
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
