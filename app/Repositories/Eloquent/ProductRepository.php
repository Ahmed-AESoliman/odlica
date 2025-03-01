<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }

    public function getFilteredProducts(array $filters, string $search, string $sortField, string $sortDirection, int $perPage)
    {
        // Build query
        $query = $this->model->query()->where('active', true);

        // Apply search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // Apply filters
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['brands'])) {
            $query->whereIn('brand_id', $filters['brands']);
        }

        if (!empty($filters['colors'])) {
            $query->whereHas('colors', function ($q) use ($filters) {
                $q->whereIn('colors.id', $filters['colors']);
            });
        }

        if (!empty($filters['sizes'])) {
            $query->whereHas('sizes', function ($q) use ($filters) {
                $q->whereIn('sizes.id', $filters['sizes']);
            });
        }

        if (isset($filters['price_min']) && isset($filters['price_max'])) {
            $query->whereBetween('price', [$filters['price_min'], $filters['price_max']]);
        }

        if (!empty($filters['on_sale'])) {
            $query->where('on_sale', true);
        }

        if (!empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        // Apply sorting
        $query->orderBy($sortField, $sortDirection);

        // Return paginated results
        return $query->paginate($perPage);
    }

    public function getMaxPrice()
    {
        return Cache::remember('max_product_price', now()->addDay(), function () {
            return $this->model->max('price');
        });
    }

    public function getFilterOptions()
    {
        return Cache::remember('filter_options', now()->addDay(), function () {
            return [
                'categories' => Category::where('active', true)->whereNotNull('parent_id')->orderBy('position')->get(),
                'brands' => Brand::orderBy('name')->get(),
                'colors' => Color::orderBy('name')->get(),
                'sizes' => Size::orderBy('type')->orderBy('name')->get(),
            ];
        });
    }
}
