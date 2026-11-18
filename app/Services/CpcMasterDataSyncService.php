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

            $branch = Branch::where(
                'code',
                (string) $location->source_location_id
            )->first();

            if ($branch) {
                DB::table('cpc_master_locations')
                    ->where('source_location_id', $location->source_location_id)
                    ->update(['local_branch_id' => $branch->id]);
            }
        }
    }

    public function syncDepartments(): void
    {
        if (! Schema::hasTable('cpc_master_functions')) {
            return;
        }

        $this->syncBranches();

        $functions = DB::table('cpc_master_functions')
            ->whereNotNull('function_name')
            ->where(function ($query) {
                $query->where('is_available', true)
                    ->orWhereNull('is_available');
            })
            ->orderBy('function_name')
            ->get([
                'source_function_id',
                'function_name',
            ]);

        foreach ($functions as $function) {
            $name = trim((string) $function->function_name);

            if ($name === '') {
                continue;
            }

            Department::updateOrCreate(
                [
                    'code' => (string) $function->source_function_id,
                ],
                [
                    'name' => $name,
                    'source_location_id' => null,
                    'source_function_id' => $function->source_function_id,
                    'branch_id' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
