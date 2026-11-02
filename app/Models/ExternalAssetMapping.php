<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalAssetMapping extends Model
{
    protected $fillable = [
        'source_system',
        'source_asset_id',
        'item_id',
        'source_data',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'source_data' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
