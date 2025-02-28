<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'featured'];

    protected $casts = [
        'featured' => 'boolean',
    ];

    /**
     * Get the products from this brand
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include featured brands
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
