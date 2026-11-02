<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'epf_no',
        'name',
        'designation',
        'branch_id',
        'department_id',
        'function_unit_id',
        'email',
        'phone',
    ];

    /*
    |--------------------------------------------------------------------------
    | Organizational Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Employee belongs to a branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Employee belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Employee belongs to a function unit.
     */
    public function functionUnit(): BelongsTo
    {
        return $this->belongsTo(FunctionUnit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Account Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * Employee login account.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * All inventory assignment records for this employee.
     */
    public function itemAssignments(): HasMany
    {
        return $this->hasMany(ItemAssignment::class);
    }

    /**
     * Currently assigned inventory items.
     */
    public function activeItemAssignments(): HasMany
    {
        return $this->hasMany(ItemAssignment::class)
            ->whereNull('returned_date');
    }

    /**
     * Previously returned inventory items.
     */
    public function returnedItemAssignments(): HasMany
    {
        return $this->hasMany(ItemAssignment::class)
            ->whereNotNull('returned_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Repair Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * Repair records associated with this employee.
     */
    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RDD Relationship
    |--------------------------------------------------------------------------
    */

    /**
     * RDD transaction records associated with this employee.
     */
    public function rddTransactions(): HasMany
    {
        return $this->hasMany(RddTransaction::class, 'employee_id');
    }
}