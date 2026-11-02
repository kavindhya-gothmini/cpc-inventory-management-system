<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type_id',

        'asset_number',
        'serial_number',

        /*
         * New Make / Model relationships.
         */
        'make_id',
        'model_id',

        /*
         * Legacy text fields.
         *
         * These are kept temporarily so existing inventory data
         * is not broken during the migration to the new system.
         */
        'make',
        'model',

        'ip_address',
        'workstation_id',

        'branch_id',
        'department_id',
        'function_unit_id',

        'current_employee_id',

        'status',

        'description',
        'notes',
    ];


    /*
    |--------------------------------------------------------------------------
    | ITEM TYPE
    |--------------------------------------------------------------------------
    */

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }


    /*
    |--------------------------------------------------------------------------
    | MAKE
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Item
    |   ↓
    | make_id = 1
    |   ↓
    | HP
    |
    */

    public function make(): BelongsTo
    {
        return $this->belongsTo(
            Make::class,
            'make_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MODEL
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Item
    |   ↓
    | model_id = 5
    |   ↓
    | HP ProBook 450 G9
    |
    */

    public function itemModel(): BelongsTo
    {
        return $this->belongsTo(
            ItemModel::class,
            'model_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

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
    | CURRENT EMPLOYEE
    |--------------------------------------------------------------------------
    */

    public function currentEmployee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'current_employee_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SPECIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function specification(): HasOne
    {
        return $this->hasOne(ItemSpecification::class);
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function assignments(): HasMany
    {
        return $this->hasMany(ItemAssignment::class);
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY HISTORY
    |--------------------------------------------------------------------------
    */

    public function histories(): HasMany
    {
        return $this->hasMany(ItemHistory::class);
    }


    /*
    |--------------------------------------------------------------------------
    | REPAIR MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }


    /*
    |--------------------------------------------------------------------------
    | RDD MANAGEMENT
    |--------------------------------------------------------------------------
    |
    | An inventory item can have multiple RDD records over its lifetime.
    | The current active RDD record is controlled by the RDD workflow.
    |
    */

    public function rddTransactions(): HasMany
    {
        return $this->hasMany(
            RddTransaction::class,
            'item_id'
        );
    }
}