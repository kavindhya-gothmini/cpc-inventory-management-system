<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePartStockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'movement_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'repair_id',
        'reference',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Spare Part
    |--------------------------------------------------------------------------
    */

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Repair
    |--------------------------------------------------------------------------
    */

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }
}