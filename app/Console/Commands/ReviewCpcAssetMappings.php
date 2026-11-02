<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewCpcAssetMappings extends Command
{
    protected $signature = 'cpc:assets:review-mappings';

    protected $description =
        'Review CPC asset values and local mapping candidates without changing data';

    public function handle(): int
    {
        $sourceSystem = 'CPC_HelpDesk_And_Asset_Management';

        $normalize = static fn ($value): string =>
            Str::lower(trim((string) ($value ?? '')));

        $ruleKey = static fn ($type, $value): string =>
            $normalize($type) . '|' . $normalize($value);

        // Load saved mapping rules from the local MySQL database.
        $rules = DB::connection('mysql')
            ->table('external_asset_value_mappings')
            ->where('source_system', $sourceSystem)
            ->get();

        $ruleLookup = $rules->keyBy(
            fn ($rule) => $ruleKey(
                $rule->mapping_type,
                $rule->source_value
            )
        );

        $ruleState = static function ($rule): string {
            if ($rule === null) {
                return 'NO RULE';
            }

            return (bool) $rule->is_approved
                ? 'APPROVED'
                : 'PENDING APPROVAL';
        };

        // Load local item types for candidate comparison only.
        $localTypes = DB::connection('mysql')
            ->table('item_types')
            ->get(['id', 'name'])
            ->keyBy(fn ($item) => $normalize($item->name));

        // Load local branches for candidate comparison only.
        $localBranches = DB::connection('mysql')
            ->table('branches')
            ->get(['id', 'name', 'code']);

        $branchesByCode = $localBranches
            ->filter(
                fn ($branch) =>
                    trim((string) $branch->code) !== ''
            )
            ->keyBy(
                fn ($branch) => $normalize($branch->code)
            );

        /*
         * SECTION 1: SOURCE ITEM TYPES
         * Exact-name matches are candidates, not automatic approvals.
         */
        $sourceTypes = DB::connection('sqlsrv_assets')
            ->table('Asset')
            ->select('Type')
            ->distinct()
            ->orderBy('Type')
            ->pluck('Type');

        $typeRows = [];

        foreach ($sourceTypes as $sourceType) {
            $name = trim((string) ($sourceType ?? ''));

            $candidate = $localTypes->get(
                $normalize($name)
            );

            $rule = $name !== ''
                ? $ruleLookup->get(
                    $ruleKey('item_type', $name)
                )
                : null;

            $typeRows[] = [
                $name !== '' ? $name : '(blank)',
                $candidate
                    ? "{$candidate->name} [ID {$candidate->id}] - candidate only"
                    : 'UNMAPPED',
                $ruleState($rule),
                $rule?->notes ?? '',
            ];
        }

        $this->info('SOURCE ITEM TYPES');

        $this->table(
            [
                'CPC source type',
                'Local candidate',
                'Rule status',
                'Notes',
            ],
            $typeRows
        );

        /*
         * SECTION 2: SOURCE STATUSES
         */
        $sourceStatuses = DB::connection('sqlsrv_assets')
            ->table('Asset')
            ->select('Status')
            ->distinct()
            ->orderBy('Status')
            ->pluck('Status');

        $statusRows = [];

        foreach ($sourceStatuses as $sourceStatus) {
            $status = trim((string) ($sourceStatus ?? ''));

            $rule = $status !== ''
                ? $ruleLookup->get(
                    $ruleKey('status', $status)
                )
                : null;

            $statusRows[] = [
                $status !== '' ? $status : '(blank)',
                $rule?->local_status ?? 'REVIEW',
                $ruleState($rule),
                $rule?->notes ?? '',
            ];
        }

        $this->newLine();
        $this->info('SOURCE STATUSES');

        $this->table(
            [
                'CPC source status',
                'Proposed local status',
                'Rule status',
                'Notes',
            ],
            $statusRows
        );

        /*
         * SECTION 3: SOURCE BRANCHES
         */
        $sourceBranches = DB::connection('sqlsrv_assets')
            ->table('Asset as a')
            ->leftJoin(
                'Department as d',
                'd.Name',
                '=',
                'a.AssignedBranch'
            )
            ->whereNotNull('a.AssignedBranch')
            ->where('a.AssignedBranch', '<>', '')
            ->select([
                'a.AssignedBranch',
                'd.LocationCode as SourceBranchCode',
            ])
            ->distinct()
            ->orderBy('a.AssignedBranch')
            ->get();

        $branchRows = [];

        foreach ($sourceBranches as $sourceBranch) {
            $name = trim(
                (string) $sourceBranch->AssignedBranch
            );

            $code = trim(
                (string) ($sourceBranch->SourceBranchCode ?? '')
            );

            $candidate = $code !== ''
                ? $branchesByCode->get(
                    $normalize($code)
                )
                : null;

            $rule = $code !== ''
                ? $ruleLookup->get(
                    $ruleKey('branch', $code)
                )
                : null;

            if ($rule === null && $name !== '') {
                $rule = $ruleLookup->get(
                    $ruleKey('branch', $name)
                );
            }

            $branchRows[] = [
                $name,
                $code !== '' ? $code : '(missing)',
                $candidate
                    ? "{$candidate->name} [ID {$candidate->id}] - candidate only"
                    : 'UNMAPPED',
                $ruleState($rule),
                $rule?->notes ?? '',
            ];
        }

        $this->newLine();
        $this->info('SOURCE BRANCHES');

        $this->table(
            [
                'CPC source branch',
                'Source code',
                'Local candidate',
                'Rule status',
                'Notes',
            ],
            $branchRows
        );

        /*
         * SECTION 4: SAVED MAPPING PROPOSALS
         * Read-only display of the records stored in local MySQL.
         */
        $savedRules = DB::connection('mysql')
            ->table('external_asset_value_mappings')
            ->where('source_system', $sourceSystem)
            ->orderBy('mapping_type')
            ->orderBy('source_value')
            ->get();

        $savedRows = [];

        foreach ($savedRules as $savedRule) {
            $target = match ($savedRule->mapping_type) {
                'item_type' =>
                    $savedRule->local_item_type_id !== null
                        ? 'Local item type ID '
                            . $savedRule->local_item_type_id
                        : 'NOT ASSIGNED',

                'branch' =>
                    $savedRule->local_branch_id !== null
                        ? 'Local branch ID '
                            . $savedRule->local_branch_id
                        : 'NOT ASSIGNED',

                'status' =>
                    $savedRule->local_status ?? 'NOT ASSIGNED',

                default => 'UNKNOWN MAPPING TYPE',
            };

            $savedRows[] = [
                $savedRule->mapping_type,
                $savedRule->source_value,
                $target,
                (bool) $savedRule->is_approved
                    ? 'APPROVED'
                    : 'PENDING APPROVAL',
                $savedRule->notes ?? '',
            ];
        }

        $this->newLine();
        $this->info('SAVED MAPPING PROPOSALS');

        if (empty($savedRows)) {
            $this->warn('No saved mapping proposals found.');
        } else {
            $this->table(
                [
                    'Mapping type',
                    'Source value',
                    'Local target',
                    'Approval',
                    'Notes',
                ],
                $savedRows
            );
        }

        $approvedCount = $savedRules
            ->filter(
                fn ($rule) => (bool) $rule->is_approved
            )
            ->count();

        $pendingCount = $savedRules->count() - $approvedCount;

        $this->newLine();
        $this->info(
            'Saved mapping proposals: ' . $savedRules->count()
        );

        $this->info(
            'Approved mappings: ' . $approvedCount
        );

        $this->info(
            'Pending approval: ' . $pendingCount
        );

        $this->warn(
            'READ-ONLY REVIEW: this command does not create, approve, or modify mapping rules.'
        );

        return self::SUCCESS;
    }
}
