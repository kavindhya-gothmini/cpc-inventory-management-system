<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'ram',
        'storage',
        'processor',
        'graphics_card',
        'operating_system',
        'additional_specifications',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}