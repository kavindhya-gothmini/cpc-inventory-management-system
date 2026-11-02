<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemporaryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_type',
        'description',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | User Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User who created the temporary item.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * User who last updated the temporary item.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Issue Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Issues associated with this temporary item.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(
            TemporaryItemIssue::class,
            'temporary_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isIssued(): bool
    {
        return $this->status === 'issued';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }
}

