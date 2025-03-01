<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function findById($id);
    public function findBySlug($slug);
    public function getFilteredProducts(array $filters, string $search, string $sortField, string $sortDirection, int $perPage);
    public function getMaxPrice();
    public function getFilterOptions();
}
