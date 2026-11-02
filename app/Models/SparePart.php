<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SparePart extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'branch_id',
        'part_code',
        'part_name',
        'category',
        'description',
        'supplier_name',
        'supplier_reference',
        'quantity',
        'minimum_stock',
        'unit_price',
        'warranty_start',
        'warranty_end',
        'location',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'branch_id' => 'integer',
        'quantity' => 'integer',
        'minimum_stock' => 'integer',
        'unit_price' => 'decimal:2',
        'warranty_start' => 'date',
        'warranty_end' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Repair Usage
    |--------------------------------------------------------------------------
    */

    public function repairSpareParts(): HasMany
    {
        return $this->hasMany(RepairSparePart::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Movements
    |--------------------------------------------------------------------------
    */

    public function stockMovements(): HasMany
    {
        return $this->hasMany(SparePartStockMovement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Supplier Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(SparePartDocument::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Warranty Helpers
    |--------------------------------------------------------------------------
    */

    public function getWarrantyStatusAttribute(): string
    {
        if (!$this->warranty_start && !$this->warranty_end) {
            return 'No Warranty';
        }

        if (
            $this->warranty_end &&
            $this->warranty_end->isPast()
        ) {
            return 'Expired';
        }

        if (
            $this->warranty_end &&
            now()->diffInDays($this->warranty_end, false) <= 30
        ) {
            return 'Expiring Soon';
        }

        return 'Active';
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Helpers
    |--------------------------------------------------------------------------
    */

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }
}
