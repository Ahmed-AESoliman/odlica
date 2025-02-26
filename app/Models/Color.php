<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'value'];

    /**
     * Get the products with this color
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color');
    }

    /**
     * Get product variants with this color
     */
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variant_color');
    }
}