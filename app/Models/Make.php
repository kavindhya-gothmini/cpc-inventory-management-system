<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Make extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all models belonging to this make.
     */
    public function itemModels(): HasMany
    {
        return $this->hasMany(ItemModel::class);
    }

    /**
     * Get only active models belonging to this make.
     */
    public function activeItemModels(): HasMany
    {
        return $this->hasMany(ItemModel::class)
            ->where('is_active', true);
    }
}