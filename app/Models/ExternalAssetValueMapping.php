<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalAssetValueMapping extends Model
{
    protected $fillable = [
        'source_system',
        'mapping_type',
        'source_value',
        'local_item_type_id',
        'local_branch_id',
        'local_status',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function localItemType(): BelongsTo
    {
        return $this->belongsTo(
            ItemType::class,
            'local_item_type_id'
        );
    }

    public function localBranch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class,
            'local_branch_id'
        );
    }
}
