<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'employee_id',
        'branch_id',
        'department_id',
        'function_unit_id',
        'workstation_id',
        'assigned_date',
        'returned_date',
        'remarks',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function functionUnit(): BelongsTo
    {
        return $this->belongsTo(FunctionUnit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isIssued(): bool
    {
        return is_null($this->returned_date);
    }

    public function isReturned(): bool
    {
        return ! is_null($this->returned_date);
    }

    public function getStatusAttribute(): string
    {
        return $this->isIssued()
            ? 'Issued'
            : 'Returned';
    }
}