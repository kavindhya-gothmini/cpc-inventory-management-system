<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairAccessory extends Model
{
    use HasFactory;

    protected $table = 'repair_accessories';

    protected $fillable = [
        'repair_id',
        'accessory_name',
        'quantity',
        'issued_date',
        'returned',
        'returned_date',
        'returned_by',
        'return_remarks',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'returned_date' => 'date',
        'returned' => 'boolean',
        'quantity' => 'integer',
    ];

    /**
     * Repair associated with this accessory.
     */
    public function repair(): BelongsTo
    {
        return $this->belongsTo(
            Repair::class,
            'repair_id'
        );
    }
}