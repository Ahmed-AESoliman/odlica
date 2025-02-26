<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'value', 'type'];

    /**
     * Get the products with this size
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size');
    }

    /**
     * Get product variants with this size
     */
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variant_size');
    }

    /**
     * Scope a query to only include sizes of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}