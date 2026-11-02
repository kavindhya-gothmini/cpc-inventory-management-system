<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RddTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'rdd_transactions';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'reference_no',
        'item_id',
        'employee_id',
        'rdd_date',
        'reason',
        'recommendation',
        'remarks',
        'status',
        'decision',
        'received_date',
        'completed_date',
        'work_performed',
        'final_condition',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'rdd_date' => 'date',
        'received_date' => 'date',
        'completed_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * RDD transaction belongs to an inventory item.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * RDD transaction belongs to an employee.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * RDD transaction has many history records.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(
            RddHistory::class,
            'rdd_transaction_id'
        )->latest();
    }

    /**
     * Scope for active RDD transactions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a particular status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check whether this RDD transaction is completed.
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, [
            'completed',
            'returned',
            'beyond_repair',
        ], true);
    }
}