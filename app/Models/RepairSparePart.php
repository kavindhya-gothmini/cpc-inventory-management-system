<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairSparePart extends Model
{
    use HasFactory;

    protected $table = 'repair_spare_parts';

    protected $fillable = [
        'repair_id',
        'spare_part_id',
        'quantity_used',
        'unit_price',
        'total_price',
        'remarks',
    ];

    protected $casts = [
        'quantity_used' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Repair
    |--------------------------------------------------------------------------
    */

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Spare Part
    |--------------------------------------------------------------------------
    */

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }
}