<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repairs';

    protected $fillable = [
        'item_id',
        'repair_reference',
        'date_sent',
        'employee_id',
        'carrier',
        'problem_description',
        'technician',
        'status',
        'date_returned',
        'repair_cost',
        'remarks',
    ];

    protected $casts = [
        'date_sent' => 'date',
        'date_returned' => 'date',
        'repair_cost' => 'decimal:2',
    ];

    /**
     * Inventory item associated with this repair.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(
            Item::class,
            'item_id'
        );
    }

    /**
     * Employee associated with this repair.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    /**
     * Collection / handover information.
     *
     * One repair has one collection record.
     */
    public function collection(): HasOne
    {
        return $this->hasOne(
            RepairCollection::class,
            'repair_id'
        );
    }

    /**
     * Accessories handed over with the item.
     *
     * One repair can have multiple accessories.
     */
    public function accessories(): HasMany
    {
        return $this->hasMany(
            RepairAccessory::class,
            'repair_id'
        );
    }

    /**
     * Repair spare-part records.
     */
    public function repairSpareParts(): HasMany
    {
        return $this->hasMany(
            RepairSparePart::class,
            'repair_id'
        );
    }

    /**
     * Spare parts used during this repair.
     */
    public function spareParts()
    {
        return $this->belongsToMany(
            SparePart::class,
            'repair_spare_parts',
            'repair_id',
            'spare_part_id'
        )->withPivot([
            'quantity_used',
            'unit_price',
            'total_price',
            'remarks',
        ])->withTimestamps();
    }
}