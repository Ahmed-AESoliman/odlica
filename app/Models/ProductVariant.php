<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variant
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the color of this variant
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'variant_color');
    }

    /**
     * Get the size of this variant
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'variant_size');
    }

    /**
     * Get the price to display (sale price if available, otherwise regular price)
     */
    public function getDisplayPriceAttribute()
    {
        if ($this->sale_price) {
            return $this->sale_price;
        }

        if ($this->price) {
            return $this->price;
        }

        return $this->product->display_price;
    }
}