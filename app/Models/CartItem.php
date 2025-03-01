<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'product_id',
        'product_variant_id',
        'quantity'
    ];

    /**
     * Get the product that belongs to this cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant that belongs to this cart item.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Calculate the total price for this cart item
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        // If there's a variant, use its price, otherwise use the product price
        $price = $this->variant
            ? ($this->variant->sale_price ?? $this->variant->price)
            : ($this->product->sale_price ?? $this->product->price);

        return $price * $this->quantity;
    }
}
