<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SparePartSupplier extends Model
{
    use HasFactory;

    protected $table = 'spare_part_suppliers';

    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'contact_person',
        'phone',
        'alternative_phone',
        'email',
        'address',
        'city',
        'supplier_type',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Supplier documents.
     *
     * A supplier can have multiple official
     * documents attached to its profile.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(
            SupplierDocument::class,
            'spare_part_supplier_id'
        );
    }
}
