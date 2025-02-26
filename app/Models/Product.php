<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'sku',
        'image',
        'active',
        'featured',
        'category_id',
        'brand_id',
        'specifications',
        'average_rating',
        'on_sale',
        'discount_percentage',
        'new_until'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'active' => 'boolean',
        'featured' => 'boolean',
        'on_sale' => 'boolean',
        'specifications' => 'array',
        'average_rating' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'new_until' => 'datetime',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand of the product
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the product variants
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the product images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * Get the colors available for this product
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }

    /**
     * Get the sizes available for this product
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    /**
     * Get the price to display (sale price if available, otherwise regular price)
     */
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Determine if the product has a discount
     */
    public function getHasDiscountAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Get the discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Scope a query to only include active products
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include products on sale
     */
    public function scopeOnSale($query)
    {
        return $query->where('on_sale', true);
    }

    /**
     * Scope a query to only include products in a specific price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope a query to products with specific colors
     */
    public function scopeWithColors($query, array $colorIds)
    {
        return $query->whereHas('colors', function ($q) use ($colorIds) {
            $q->whereIn('colors.id', $colorIds);
        });
    }

    /**
     * Scope a query to products with specific sizes
     */
    public function scopeWithSizes($query, array $sizeIds)
    {
        return $query->whereHas('sizes', function ($q) use ($sizeIds) {
            $q->whereIn('sizes.id', $sizeIds);
        });
    }

    /**
     * Scope a query to products with a minimum rating
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('average_rating', '>=', $rating);
    }

    /**
     * Scope a query to search products by name or description
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}