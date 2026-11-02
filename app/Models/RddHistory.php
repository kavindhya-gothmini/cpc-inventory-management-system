<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RddHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'rdd_histories';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'rdd_transaction_id',
        'old_status',
        'new_status',
        'remarks',
        'recommendation',
        'changed_by',
    ];

    /**
     * RDD history belongs to an RDD transaction.
     */
    public function rddTransaction(): BelongsTo
    {
        return $this->belongsTo(
            RddTransaction::class,
            'rdd_transaction_id'
        );
    }
}