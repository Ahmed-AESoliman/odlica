<?php

namespace App\Services\Product;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getProduct($id)
    {
        return $this->repository->findById($id);
    }

    public function getProductBySlug($slug)
    {
        return $this->repository->findBySlug($slug);
    }

    public function getFilteredProducts(array $filters, string $search, string $sortField, string $sortDirection, int $perPage)
    {
        // Create cache key from all parameters
        $cacheKey = 'products:' . md5(json_encode([
            'filters' => $filters,
            'search' => $search,
            'sort' => $sortField . '-' . $sortDirection,
            'page' => request()->input('page', 1),
            'perPage' => $perPage
        ]));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters, $search, $sortField, $sortDirection, $perPage) {
            return $this->repository->getFilteredProducts($filters, $search, $sortField, $sortDirection, $perPage);
        });
    }

    public function getMaxPrice()
    {
        return $this->repository->getMaxPrice();
    }

    public function getFilterOptions()
    {
        return $this->repository->getFilterOptions();
    }
}
