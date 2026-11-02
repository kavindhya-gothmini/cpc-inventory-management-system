<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryItemIssueHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'temporary_item_issue_id',
        'action',
        'description',
        'changed_by',
        'changed_by_name',
        'changed_by_epf',
        'changed_by_role',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Temporary Item Issue
    |--------------------------------------------------------------------------
    */

    public function temporaryItemIssue(): BelongsTo
    {
        return $this->belongsTo(
            TemporaryItemIssue::class,
            'temporary_item_issue_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User Who Made The Change
    |--------------------------------------------------------------------------
    */

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}

