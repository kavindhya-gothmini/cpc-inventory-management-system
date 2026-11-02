<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePartDocument extends Model
{
    use HasFactory;

    protected $table = 'spare_part_documents';

    protected $fillable = [
        'spare_part_id',
        'document_name',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Spare Part
    |--------------------------------------------------------------------------
    */

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(
            SparePart::class,
            'spare_part_id'
        );
    }
}