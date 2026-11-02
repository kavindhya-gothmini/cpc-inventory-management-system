<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProposeCpcAssetMappings extends Command
{
    protected $signature = 'cpc:assets:propose-mappings';

    protected $description =
        'Add unapproved CPC asset mapping proposals to the local database';

    public function handle(): int
    {
        $sourceSystem = 'CPC_HelpDesk_And_Asset_Management';

        $this->warn(
            'This command will create pending proposals in the local MySQL database.'
        );

        $this->info(
            'It will not approve rules or modify the CPC SQL Server.'
        );

        if (! $this->confirm('Continue?', false)) {
            $this->info('Cancelled. No changes made.');

            return self::SUCCESS;
        }

        $proposals = [
            [
                'mapping_type' => 'item_type',
                'source_value' => 'CPU / System Unit',
                'notes' => 'Pending review: confirm the correct local item type.',
            ],
            [
                'mapping_type' => 'item_type',
                'source_value' => 'Monitor',
                'notes' => 'Pending review: exact-name match is only a candidate.',
            ],
            [
                'mapping_type' => 'item_type',
                'source_value' => 'Printer',
                'notes' => 'Pending review: no matching local item type confirmed.',
            ],
            [
                'mapping_type' => 'item_type',
                'source_value' => 'UPS',
                'notes' => 'Pending review: confirm the correct local item type.',
            ],
            [
                'mapping_type' => 'status',
                'source_value' => 'Available',
                'notes' => 'Pending review: confirm the CPC-to-local status meaning.',
            ],
            [
                'mapping_type' => 'status',
                'source_value' => 'Scrapped',
                'notes' => 'Pending review: confirm the local scrapped status mapping.',
            ],
            [
                'mapping_type' => 'status',
                'source_value' => 'Transferred',
                'notes' => 'Pending review: confirm the local transferred status mapping.',
            ],
            [
                'mapping_type' => 'branch',
                'source_value' => '2001',
                'notes' => 'Pending review: code candidate for R/O NORTH CENTRAL; confirm with CPC IT.',
            ],
            [
                'mapping_type' => 'branch',
                'source_value' => '2021',
                'notes' => 'Pending review: KOLLONNAWA has no confirmed local branch mapping.',
            ],
        ];

        $created = 0;
        $skipped = 0;

        DB::connection('mysql')->transaction(function () use (
            $sourceSystem,
            $proposals,
            &$created,
            &$skipped
        ) {
            foreach ($proposals as $proposal) {
                $exists = DB::connection('mysql')
                    ->table('external_asset_value_mappings')
                    ->where('source_system', $sourceSystem)
                    ->where('mapping_type', $proposal['mapping_type'])
                    ->where('source_value', $proposal['source_value'])
                    ->exists();

                if ($exists) {
                    $skipped++;

                    $this->line(
                        'Skipped existing proposal: '
                        . $proposal['mapping_type']
                        . ' / '
                        . $proposal['source_value']
                    );

                    continue;
                }

                DB::connection('mysql')
                    ->table('external_asset_value_mappings')
                    ->insert([
                        'source_system' => $sourceSystem,
                        'mapping_type' => $proposal['mapping_type'],
                        'source_value' => $proposal['source_value'],
                        'local_item_type_id' => null,
                        'local_branch_id' => null,
                        'local_status' => null,
                        'is_approved' => false,
                        'approved_by' => null,
                        'approved_at' => null,
                        'notes' => $proposal['notes'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                $created++;
            }
        });

        $this->newLine();
        $this->info("Proposals created: {$created}");
        $this->info("Existing proposals skipped: {$skipped}");
        $this->warn('All created proposals remain unapproved.');

        return self::SUCCESS;
    }
}
