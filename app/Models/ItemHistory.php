<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'event_type',
        'from_status',
        'to_status',
        'description',
        'employee_id',
        'event_date',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}