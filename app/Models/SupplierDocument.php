<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDocument extends Model
{
    use HasFactory;

    protected $table = 'supplier_documents';

    protected $fillable = [
        'spare_part_supplier_id',
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

    /**
     * Supplier that owns this document.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            SparePartSupplier::class,
            'spare_part_supplier_id'
        );
    }
}
