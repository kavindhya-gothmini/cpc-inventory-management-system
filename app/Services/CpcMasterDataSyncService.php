<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CpcMasterDataSyncService
{
    public function syncBranches(): void
    {
        if (! Schema::hasTable('cpc_master_locations')) {
            return;
        }

        $locations = DB::table('cpc_master_locations')
            ->whereNotNull('location_name')
            ->where(function ($query) {
                $query->where('is_available', true)
                    ->orWhereNull('is_available');
            })
            ->orderBy('location_name')
            ->get([
                'source_location_id',
                'location_name',
            ]);

        foreach ($locations as $location) {
            $name = trim((string) $location->location_name);

            if ($name === '') {
                continue;
            }

            Branch::updateOrCreate(
                ['code' => (string) $location->source_location_id],
                [
                    'name' => $name,
                    'location' => $name,
                    'is_active' => true,
                ]
            );
        }
    }

    public function syncDepartments(): void
    {
        if (! Schema::hasTable('cpc_master_locations')) {
            return;
        }

        $this->syncBranches();

        $branchIdsByCode = Branch::query()
            ->whereNotNull('code')
            ->pluck('id', 'code');

        $locations = DB::table('cpc_master_locations')
            ->whereNotNull('location_name')
            ->where(function ($query) {
                $query->where('is_available', true)
                    ->orWhereNull('is_available');
            })
            ->orderBy('location_name')
            ->get([
                'source_location_id',
                'location_name',
            ]);

        foreach ($locations as $location) {
            $name = trim((string) $location->location_name);

            if ($name === '') {
                continue;
            }

            Department::updateOrCreate(
                [
                    'source_location_id' => $location->source_location_id,
                ],
                [
                    'name' => $name,
                    'code' => (string) $location->source_location_id,
                    'branch_id' => $branchIdsByCode->get(
                        (string) $location->source_location_id
                    ),
                    'is_active' => true,
                ]
            );
        }
    }
}
