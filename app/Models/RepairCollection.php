<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairCollection extends Model
{
    use HasFactory;

    protected $table = 'repair_collections';

    protected $fillable = [
        'repair_id',
        'collector_name',
        'collector_epf_no',
        'collector_contact',
        'issued_date',
        'remarks',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    /**
     * Repair associated with this collection.
     */
    public function repair(): BelongsTo
    {
        return $this->belongsTo(
            Repair::class,
            'repair_id'
        );
    }
}