<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemporaryItemIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_datetime',
        'temporary_item_id',
        'item_code',
        'item_type',
        'department_id',
        'function_unit_id',
        'event',
        'requesting_employee_id',
        'collecting_employee_id',
        'received_datetime',
        'returning_employee_id',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_datetime' => 'datetime',
        'received_datetime' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Temporary Item Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * Pre-registered temporary item used for this issue.
     */
    public function temporaryItem(): BelongsTo
    {
        return $this->belongsTo(
            TemporaryItem::class,
            'temporary_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Organization Relationships
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    public function functionUnit(): BelongsTo
    {
        return $this->belongsTo(
            FunctionUnit::class,
            'function_unit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Employee who requested the temporary item.
     */
    public function requestingEmployee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'requesting_employee_id'
        );
    }

    /**
     * Employee who collects the temporary item from IT.
     */
    public function collectingEmployee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'collecting_employee_id'
        );
    }

    /**
     * Employee who returns the temporary item to IT.
     */
    public function returningEmployee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'returning_employee_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function histories(): HasMany
    {
        return $this->hasMany(
            TemporaryItemIssueHistory::class,
            'temporary_item_issue_id'
        )->latest('changed_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isIssued(): bool
    {
        return $this->status === 'issued';
    }

    public function isReceived(): bool
    {
        return $this->status === 'received';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}

