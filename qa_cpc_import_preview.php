<?php

use Illuminate\Support\Facades\DB;

$source = 'CPC_HelpDesk_And_Asset_Management';
$connection = DB::connection('sqlsrv_assets');

/*
|--------------------------------------------------------------------------
| READ-ONLY CPC ASSET IMPORT PREVIEW
|--------------------------------------------------------------------------
| This script must not insert, update, or delete records.
| It only reads source data and local mapping/item records.
|--------------------------------------------------------------------------
*/

$normalize = static function ($value): string {
    return strtoupper(trim((string) ($value ?? '')));
};

$mapKey = static function ($type, $value) use ($normalize): string {
    return $normalize($type) . '|' . $normalize($value);
};

/*
|--------------------------------------------------------------------------
| Load source data
|--------------------------------------------------------------------------
*/

$assets = $connection
    ->table('Asset')
    ->orderBy('Id')
    ->get();

$sourceDepartments = $connection
    ->table('Department')
    ->get()
    ->keyBy(fn ($row) => $normalize($row->Name));

/*
|--------------------------------------------------------------------------
| Load local mappings and reference data
|--------------------------------------------------------------------------
*/

$valueMappings = DB::table('external_asset_value_mappings')
    ->where('source_system', $source)
    ->get()
    ->keyBy(fn ($row) => $mapKey(
        $row->mapping_type,
        $row->source_value
    ));

$branches = DB::table('branches')
    ->get()
    ->keyBy('id');

$itemTypes = DB::table('item_types')
    ->get()
    ->keyBy('id');

$localItems = DB::table('items')->get();

$itemsById = $localItems->keyBy('id');

/*
|--------------------------------------------------------------------------
| Build normalized duplicate lookup indexes
|--------------------------------------------------------------------------
| Keep every matching local item ID so conflicts are visible.
|--------------------------------------------------------------------------
*/

$assetNumberIndex = [];
$serialNumberIndex = [];

foreach ($localItems as $localItem) {
    $assetNumber = $normalize($localItem->asset_number);
    $serialNumber = $normalize($localItem->serial_number);

    if ($assetNumber !== '') {
        $assetNumberIndex[$assetNumber][] = $localItem->id;
    }

    if ($serialNumber !== '') {
        $serialNumberIndex[$serialNumber][] = $localItem->id;
    }
}

/*
|--------------------------------------------------------------------------
| Load existing source asset mappings
|--------------------------------------------------------------------------
*/

$existingMappings = DB::table('external_asset_mappings')
    ->where('source_system', $source)
    ->get()
    ->keyBy(fn ($row) => (string) $row->source_asset_id);

/*
|--------------------------------------------------------------------------
| Preview each source asset
|--------------------------------------------------------------------------
*/

$preview = [];

foreach ($assets as $asset) {
    $sourceId = (string) $asset->Id;

    $assetNumber = trim((string) ($asset->Barcode ?? ''));
    $serialNumber = trim((string) ($asset->SerialNumber ?? ''));
    $sourceBranchName = trim((string) ($asset->AssignedBranch ?? ''));

    $typeMap = $valueMappings->get(
        $mapKey('item_type', $asset->Type)
    );

    $statusMap = $valueMappings->get(
        $mapKey('status', $asset->Status)
    );

    /*
    |--------------------------------------------------------------------------
    | Resolve branch without guessing
    |--------------------------------------------------------------------------
    */

    $sourceDepartment = $sourceBranchName !== ''
        ? $sourceDepartments->get($normalize($sourceBranchName))
        : null;

    $branchCode = $sourceDepartment
        ? trim((string) ($sourceDepartment->LocationCode ?? ''))
        : '';

    $branchMap = $branchCode !== ''
        ? $valueMappings->get($mapKey('branch', $branchCode))
        : null;

    $mappedBranchId = $branchMap?->local_branch_id;

    $mappedBranchExists = $mappedBranchId !== null
        && $branches->has($mappedBranchId);

    $branchApproved = $branchMap !== null
        && (bool) $branchMap->is_approved
        && $mappedBranchId !== null
        && $mappedBranchExists;

    /*
    |--------------------------------------------------------------------------
    | Validate item type and status mappings
    |--------------------------------------------------------------------------
    */

    $mappedTypeId = $typeMap?->local_item_type_id;

    $mappedTypeExists = $mappedTypeId !== null
        && $itemTypes->has($mappedTypeId);

    $typeApproved = $typeMap !== null
        && (bool) $typeMap->is_approved
        && $mappedTypeId !== null
        && $mappedTypeExists;

    $mappedStatus = $statusMap?->local_status;

    $allowedLocalStatuses = [
        'available',
        'in_use',
        'under_repair',
        'sent_to_rdd',
        'scrapped',
        'transferred',
    ];

    $statusApproved = $statusMap !== null
        && (bool) $statusMap->is_approved
        && $mappedStatus !== null
        && in_array(
            strtolower(trim((string) $mappedStatus)),
            $allowedLocalStatuses,
            true
        );

    /*
    |--------------------------------------------------------------------------
    | Detect existing asset-number and serial-number conflicts
    |--------------------------------------------------------------------------
    */

    $assetNumberMatches = $assetNumber !== ''
        ? ($assetNumberIndex[$normalize($assetNumber)] ?? [])
        : [];

    $serialNumberMatches = $serialNumber !== ''
        ? ($serialNumberIndex[$normalize($serialNumber)] ?? [])
        : [];

    $existingSourceMapping = $existingMappings->get($sourceId);

    $mappedLocalItemId = $existingSourceMapping?->item_id;

    $mappedLocalItemExists = $mappedLocalItemId !== null
        && $itemsById->has($mappedLocalItemId);

    /*
    |--------------------------------------------------------------------------
    | Build explicit review reasons
    |--------------------------------------------------------------------------
    */

    $reasons = [];

    if ($existingSourceMapping !== null) {
        if ($mappedLocalItemId === null) {
            $reasons[] = 'EXISTING_MAPPING_HAS_NO_LOCAL_ITEM';
        } elseif (! $mappedLocalItemExists) {
            $reasons[] = 'EXISTING_MAPPING_REFERENCES_MISSING_ITEM';
        } else {
            $mappedItem = $itemsById->get($mappedLocalItemId);

            $assetNumberMatchesMappedItem = $assetNumber !== ''
                && $normalize($mappedItem->asset_number)
                    === $normalize($assetNumber);

            $serialMatchesMappedItem = $serialNumber !== ''
                && $normalize($mappedItem->serial_number)
                    === $normalize($serialNumber);

            if (
                ! $assetNumberMatchesMappedItem
                && ! $serialMatchesMappedItem
            ) {
                $reasons[] = 'EXISTING_MAPPING_IDENTITY_MISMATCH';
            } else {
                $reasons[] = 'ALREADY_MAPPED_REQUIRES_SYNC_REVIEW';
            }
        }
    }

    if ($assetNumber === '') {
        $reasons[] = 'MISSING_SOURCE_BARCODE';
    } elseif (count($assetNumberMatches) > 0) {
        $reasons[] = 'LOCAL_ASSET_NUMBER_CONFLICT';
    }

    if ($serialNumber === '') {
        $reasons[] = 'MISSING_SOURCE_SERIAL_NUMBER';
    } elseif (count($serialNumberMatches) > 0) {
        $reasons[] = 'LOCAL_SERIAL_NUMBER_CONFLICT';
    }

    if ($typeMap === null) {
        $reasons[] = 'ITEM_TYPE_MAPPING_MISSING';
    } elseif (! $mappedTypeExists) {
        $reasons[] = 'ITEM_TYPE_MAPPING_TARGET_MISSING';
    } elseif (! $typeApproved) {
        $reasons[] = 'ITEM_TYPE_MAPPING_NOT_APPROVED';
    }

    if ($statusMap === null) {
        $reasons[] = 'STATUS_MAPPING_MISSING';
    } elseif (! $statusApproved) {
        $reasons[] = 'STATUS_MAPPING_MISSING_APPROVAL_OR_INVALID_TARGET';
    }

    if ($sourceBranchName === '') {
        $reasons[] = 'SOURCE_BRANCH_BLANK';
    } elseif ($sourceDepartment === null) {
        $reasons[] = 'SOURCE_BRANCH_NAME_NOT_FOUND';
    } elseif ($branchCode === '') {
        $reasons[] = 'SOURCE_BRANCH_CODE_MISSING';
    } elseif ($branchMap === null) {
        $reasons[] = 'BRANCH_MAPPING_MISSING';
    } elseif (! $mappedBranchExists) {
        $reasons[] = 'BRANCH_MAPPING_TARGET_MISSING';
    } elseif (! $branchApproved) {
        $reasons[] = 'BRANCH_MAPPING_NOT_APPROVED';
    }

    /*
    |--------------------------------------------------------------------------
    | Determine preview decision
    |--------------------------------------------------------------------------
    */

    $hasExistingMapping = $existingSourceMapping !== null;

    $hasLocalIdentityConflict =
        count($assetNumberMatches) > 0
        || count($serialNumberMatches) > 0;

    $canPassCandidateChecks =
        ! $hasExistingMapping
        && ! $hasLocalIdentityConflict
        && $assetNumber !== ''
        && $serialNumber !== ''
        && $typeApproved
        && $statusApproved
        && $branchApproved
        && count($reasons) === 0;

    $decision = $canPassCandidateChecks
        ? 'CANDIDATE_PASS_CHECKS_FINAL_REVIEW_REQUIRED'
        : (
            $hasExistingMapping
                ? 'EXISTING_MAPPING_REQUIRES_REVIEW'
                : 'BLOCKED_OR_REQUIRES_REVIEW'
        );

    /*
    |--------------------------------------------------------------------------
    | Preserve source fields for future import/audit
    |--------------------------------------------------------------------------
    */

    $sourceData = [
        'Id' => $asset->Id,
        'SerialNumber' => $asset->SerialNumber,
        'Barcode' => $asset->Barcode,
        'Type' => $asset->Type,
        'Status' => $asset->Status,
        'PurchaseDate' => $asset->PurchaseDate,
        'WarrantyDate' => $asset->WarrantyDate,
        'AssignedToEpf' => $asset->AssignedToEpf,
        'IpAddress' => $asset->IpAddress,
        'AssignedDate' => $asset->AssignedDate,
        'AssignedDepartment' => $asset->AssignedDepartment,
        'AssignedBranch' => $asset->AssignedBranch,
        'ReceivedByPerson' => $asset->ReceivedByPerson,
        'Remarks' => $asset->Remarks,
        'ReceivedDate' => $asset->ReceivedDate,
        'DispatchedBy' => $asset->DispatchedBy,
        'BranchReceiverName' => $asset->BranchReceiverName,
        'DisposedDate' => $asset->DisposedDate,
        'DisposedBy' => $asset->DisposedBy,
        'DisposalReason' => $asset->DisposalReason,
    ];

    $preview[] = [
        'source_id' => $asset->Id,
        'source_system' => $source,

        'asset_number_candidate' => $assetNumber ?: null,
        'asset_number_matching_local_item_ids' => $assetNumberMatches,

        'serial_number_candidate' => $serialNumber ?: null,
        'serial_number_matching_local_item_ids' => $serialNumberMatches,

        'source_type' => $asset->Type,
        'local_item_type_id_candidate' => $mappedTypeId,
        'type_mapping_approved' => $typeApproved,

        'source_status' => $asset->Status,
        'local_status_candidate' => $mappedStatus,
        'status_mapping_approved' => $statusApproved,

        'source_branch' => $sourceBranchName ?: null,
        'source_branch_code' => $branchCode ?: null,
        'local_branch_id_candidate' => $mappedBranchId,
        'branch_mapping_approved' => $branchApproved,

        'existing_source_mapping' => $hasExistingMapping,
        'existing_local_item_id' => $mappedLocalItemId,
        'mapped_local_item_exists' => $mappedLocalItemExists,

        'reasons' => $reasons,
        'decision' => $decision,

        'source_data_preview' => $sourceData,
    ];
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$decisionCounts = [];

foreach ($preview as $row) {
    $decisionCounts[$row['decision']] =
        ($decisionCounts[$row['decision']] ?? 0) + 1;
}

dump([
    'mode' => 'READ_ONLY_PREVIEW',
    'writes_performed' => false,
    'source_system' => $source,
    'source_asset_count' => $assets->count(),
    'local_item_count' => $localItems->count(),

    'existing_source_mapping_count' => $existingMappings->count(),

    'approved_value_mapping_count' => $valueMappings
        ->filter(fn ($mapping) => (bool) $mapping->is_approved)
        ->count(),

    'decision_counts' => $decisionCounts,
    'preview' => $preview,
]);
