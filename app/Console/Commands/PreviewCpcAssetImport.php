<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreviewCpcAssetImport extends Command
{
    protected $signature = 'cpc:assets:preview {--limit=100}';

    protected $description =
        'Preview CPC asset mappings without inserting or modifying data';

    public function handle(): int
    {
        $sourceSystem = 'CPC_HelpDesk_And_Asset_Management';

        $source = DB::connection('sqlsrv_assets')
            ->table('Asset as a')
            ->leftJoin(
                'Department as d',
                'd.Name',
                '=',
                'a.AssignedBranch'
            )
            ->select([
                'a.Id',
                'a.Barcode',
                'a.SerialNumber',
                'a.Type',
                'a.Status',
                'a.AssignedBranch',
                'a.AssignedDepartment',
                'a.AssignedToEpf',
                'd.LocationCode as SourceBranchCode',
            ])
            ->orderBy('a.Id')
            ->limit(max(1, min(5000, (int) $this->option('limit'))))
            ->get();

        $localItems = DB::connection('mysql')
            ->table('items')
            ->get(['id', 'asset_number', 'serial_number']);

        $itemTypes = DB::connection('mysql')
            ->table('item_types')
            ->get(['id', 'name']);

        $branches = DB::connection('mysql')
            ->table('branches')
            ->get(['id', 'name', 'code']);

        $assetMappings = DB::connection('mysql')
            ->table('external_asset_mappings')
            ->where('source_system', $sourceSystem)
            ->get(['source_asset_id', 'item_id', 'last_synced_at'])
            ->keyBy('source_asset_id');

        $valueRules = DB::connection('mysql')
            ->table('external_asset_value_mappings')
            ->where('source_system', $sourceSystem)
            ->get([
                'mapping_type',
                'source_value',
                'local_item_type_id',
                'local_branch_id',
                'local_status',
                'is_approved',
            ]);

        $normalize = static fn ($value): string =>
            Str::lower(trim((string) ($value ?? '')));

        $ruleKey = static fn ($type, $value): string =>
            Str::lower(trim((string) $type))
            . '|'
            . Str::lower(trim((string) $value));

        $ruleLookup = $valueRules->keyBy(
            fn ($rule) => $ruleKey(
                $rule->mapping_type,
                $rule->source_value
            )
        );

        $existingAssetNumbers = $localItems
            ->filter(fn ($item) => trim((string) $item->asset_number) !== '')
            ->mapWithKeys(fn ($item) => [
                $normalize($item->asset_number) => (int) $item->id,
            ]);

        $existingSerialNumbers = $localItems
            ->filter(fn ($item) => trim((string) $item->serial_number) !== '')
            ->mapWithKeys(fn ($item) => [
                $normalize($item->serial_number) => (int) $item->id,
            ]);

        $localItemsById = $localItems->keyBy('id');
        $typesById = $itemTypes->keyBy('id');
        $branchesById = $branches->keyBy('id');

        $typeLookup = $itemTypes->keyBy(
            fn ($type) => $normalize($type->name)
        );

        $branchesByCode = $branches
            ->filter(fn ($branch) => trim((string) $branch->code) !== '')
            ->keyBy(fn ($branch) => $normalize($branch->code));

        // This is only a proposal for preview purposes.
        // Actual imports must check approved rules.
        $statusCandidates = [
            'available' => 'available',
        ];

        $ruleState = static function ($rule): string {
            if ($rule === null) {
                return 'NO RULE';
            }

            return (bool) $rule->is_approved
                ? 'APPROVED'
                : 'PENDING APPROVAL';
        };

        $rows = [];

        foreach ($source as $asset) {
            $sourceId = (string) $asset->Id;
            $barcode = trim((string) ($asset->Barcode ?? ''));
            $serial = trim((string) ($asset->SerialNumber ?? ''));
            $typeName = trim((string) ($asset->Type ?? ''));
            $sourceStatus = trim((string) ($asset->Status ?? ''));
            $branchName = trim((string) ($asset->AssignedBranch ?? ''));
            $branchCode = trim((string) ($asset->SourceBranchCode ?? ''));
            $departmentName = trim(
                (string) ($asset->AssignedDepartment ?? '')
            );

            $typeRule = $typeName !== ''
                ? $ruleLookup->get($ruleKey('item_type', $typeName))
                : null;

            $branchRule = $branchCode !== ''
                ? $ruleLookup->get($ruleKey('branch', $branchCode))
                : null;

            // Permit a branch-name rule as a fallback lookup key.
            if ($branchRule === null && $branchName !== '') {
                $branchRule = $ruleLookup->get(
                    $ruleKey('branch', $branchName)
                );
            }

            $statusRule = $sourceStatus !== ''
                ? $ruleLookup->get($ruleKey('status', $sourceStatus))
                : null;

            $exactType = $typeLookup->get($normalize($typeName));

            $approvedType = $typeRule !== null
                && (bool) $typeRule->is_approved
                && $typeRule->local_item_type_id !== null
                ? $typesById->get((int) $typeRule->local_item_type_id)
                : null;

            $approvedBranch = $branchRule !== null
                && (bool) $branchRule->is_approved
                && $branchRule->local_branch_id !== null
                ? $branchesById->get((int) $branchRule->local_branch_id)
                : null;

            $branchCandidate = $branchCode !== ''
                ? $branchesByCode->get($normalize($branchCode))
                : null;

            $proposedType = $approvedType
                ? "{$approvedType->name} [ID {$approvedType->id}] - APPROVED RULE"
                : ($exactType
                    ? "{$exactType->name} [ID {$exactType->id}] - EXACT-NAME CANDIDATE"
                    : 'UNMAPPED');

            $proposedBranch = $approvedBranch
                ? "{$approvedBranch->name} [ID {$approvedBranch->id}] - APPROVED RULE"
                : ($branchCandidate
                    ? "{$branchCandidate->name} [ID {$branchCandidate->id}] - CODE CANDIDATE ONLY"
                    : 'UNMAPPED');

            $proposedStatus = $statusRule !== null
                && (bool) $statusRule->is_approved
                && trim((string) $statusRule->local_status) !== ''
                ? $statusRule->local_status
                : ($statusCandidates[$normalize($sourceStatus)] ?? 'REVIEW');

            $mapping = $assetMappings->get($sourceId);
            $mappedItemId = $mapping?->item_id !== null
                ? (int) $mapping->item_id
                : null;

            $mappedLocalItem = $mappedItemId !== null
                ? $localItemsById->get($mappedItemId)
                : null;

            if ($mapping === null) {
                $mappingStatus = 'NOT MAPPED';
            } elseif ($mappedItemId === null) {
                $mappingStatus = 'MAPPING EXISTS; NO LOCAL ITEM';
            } elseif ($mappedLocalItem === null) {
                $mappingStatus = 'MAPPED ITEM NOT FOUND';
            } else {
                $mappingStatus = "MAPPED TO ITEM ID {$mappedItemId}";
            }

            $issues = [];

            if ($barcode === '') {
                $issues[] = 'Barcode missing';
            } else {
                $duplicateId = $existingAssetNumbers->get(
                    $normalize($barcode)
                );

                if ($duplicateId !== null && $duplicateId !== $mappedItemId) {
                    $issues[] = "Barcode already used by local item {$duplicateId}";
                }
            }

            if ($serial === '') {
                $issues[] = 'Serial number missing';
            } else {
                $duplicateId = $existingSerialNumbers->get(
                    $normalize($serial)
                );

                if ($duplicateId !== null && $duplicateId !== $mappedItemId) {
                    $issues[] = "Serial number already used by local item {$duplicateId}";
                }
            }

            if ($typeRule === null) {
                $issues[] = 'No item-type mapping rule';
            } elseif (!(bool) $typeRule->is_approved) {
                $issues[] = 'Item-type rule pending approval';
            } elseif ($approvedType === null) {
                $issues[] = 'Approved item-type rule has invalid local reference';
            }

            if ($statusRule === null) {
                $issues[] = 'No status mapping rule';
            } elseif (!(bool) $statusRule->is_approved) {
                $issues[] = 'Status rule pending approval';
            } elseif (trim((string) $statusRule->local_status) === '') {
                $issues[] = 'Approved status rule has no local status';
            }

            if ($branchName === '') {
                $issues[] = 'Branch missing; review required';
            } elseif ($branchRule === null) {
                $issues[] = 'No branch mapping rule';
            } elseif (!(bool) $branchRule->is_approved) {
                $issues[] = 'Branch rule pending approval';
            } elseif ($approvedBranch === null) {
                $issues[] = 'Approved branch rule has invalid local reference';
            }

            if ($departmentName === '') {
                $issues[] = 'Department missing; review required';
            }

            if ($mapping !== null && $mappedItemId !== null && $mappedLocalItem === null) {
                $issues[] = 'Mapping points to a missing local item';
            }

            $rows[] = [
                $sourceId,
                $mappingStatus,
                $barcode !== '' ? $barcode : '(blank)',
                $typeName,
                $proposedType,
                $ruleState($typeRule),
                $sourceStatus,
                $proposedStatus,
                $ruleState($statusRule),
                $branchName !== '' ? $branchName : '(blank)',
                $branchCode !== '' ? $branchCode : '(blank)',
                $proposedBranch,
                $ruleState($branchRule),
                $issues ? implode('; ', $issues) : 'READY FOR REVIEW',
            ];
        }

        $this->table(
            [
                'Source ID',
                'Asset mapping',
                'Barcode candidate',
                'Source type',
                'Local type proposal',
                'Type rule',
                'Source status',
                'Proposed status',
                'Status rule',
                'Source branch',
                'Branch code',
                'Local branch proposal',
                'Branch rule',
                'Issues',
            ],
            $rows
        );

        $this->newLine();
        $this->info('READ-ONLY PREVIEW: no data was inserted or modified.');
        $this->warn(
            'No mapping rule is approved merely because an exact name or branch code matches.'
        );
        $this->warn(
            'Barcode remains a candidate Asset No until CPC IT confirms its meaning.'
        );

        return self::SUCCESS;
    }
}
