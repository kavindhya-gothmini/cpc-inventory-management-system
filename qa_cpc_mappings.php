
<?php

use Illuminate\Support\Facades\DB;

$source = 'CPC_HelpDesk_And_Asset_Management';

DB::transaction(function () use ($source) {
    $types = [
        'CPU / System Unit' => 'Computer Equipment',
        'Printer' => 'Printing Equipment',
        'UPS' => 'Computer Equipment',
    ];

    foreach ($types as $name => $category) {
        $existing = DB::table('item_types')
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->first();

        if (!$existing) {
            DB::table('item_types')->insert([
                'name' => $name,
                'category' => $category,
                'description' => 'CPC external asset mapping QA candidate; verify before import.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    $localTypes = DB::table('item_types')->get()->keyBy('name');

    foreach (['CPU / System Unit', 'Monitor', 'Printer', 'UPS'] as $name) {
        $target = $localTypes->get($name);

        if (!$target) {
            throw new RuntimeException("Missing item type: {$name}");
        }

        DB::table('external_asset_value_mappings')
            ->where('source_system', $source)
            ->where('mapping_type', 'item_type')
            ->where('source_value', $name)
            ->update([
                'local_item_type_id' => $target->id,
                'is_approved' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'notes' => 'QA candidate populated; not approved for import.',
                'updated_at' => now(),
            ]);
    }

    foreach ([
        'Available' => 'available',
        'Scrapped' => 'scrapped',
        'Transferred' => 'transferred',
    ] as $from => $to) {
        DB::table('external_asset_value_mappings')
            ->where('source_system', $source)
            ->where('mapping_type', 'status')
            ->where('source_value', $from)
            ->update([
                'local_status' => $to,
                'is_approved' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'notes' => 'QA candidate populated; not approved for import.',
                'updated_at' => now(),
            ]);
    }

    DB::table('external_asset_value_mappings')
        ->where('source_system', $source)
        ->where('mapping_type', 'branch')
        ->whereIn('source_value', ['2001', '2021'])
        ->update([
            'local_branch_id' => null,
            'is_approved' => 0,
            'approved_by' => null,
            'approved_at' => null,
            'notes' => 'No approved branch mapping: 2001 requires confirmation; 2021 Kollonnawa excluded by user decision.',
            'updated_at' => now(),
        ]);
});

echo "QA mapping candidates updated. No assets imported; no mappings approved.\n";
