<?php

namespace App\Livewire\Products;

use App\Services\Product\ProductService;
use App\Stores\Actions\CartActions;
use App\Stores\CartStore;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListing extends Component
{
    use WithPagination;

    public $search = '';
    public $filters = [
        'category' => null,
        'brands' => [],
        'colors' => [],
        'sizes' => [],
        'price_min' => 0,
        'price_max' => 1000,
        'on_sale' => false,
        'in_stock' => false,
    ];

    // Sorting properties
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 20;

    // Services
    protected $productService;
    protected $cartStore;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => [
            'category' => null,
            'brands' => [],
            'colors' => [],
            'sizes' => [],
            'price_min' => 0,
            'price_max' => 1000,
            'on_sale' => false,
            'in_stock' => false,
        ]],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // Reset pagination on filter/search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilters()
    {
        $this->resetPage();
    }
    public function updatedSortField()
    {
        $this->resetPage();
    }
    public function updatedSortDirection()
    {
        $this->resetPage();
    }
    public function updatedPerPage()
    {
        $this->resetPage();
    }


    public function boot(ProductService $productService, CartStore $cartStore)
    {
        $this->productService = $productService;
        $this->cartStore = $cartStore;
    }

    public function mount()
    {
        if ($this->filters['price_max'] === 1000) {
            $this->filters['price_max'] = $this->productService->getMaxPrice();
        }
    }

    public function resetFilters()
    {
        $this->reset('search');
        $this->filters = [
            'category' => null,
            'brands' => [],
            'colors' => [],
            'sizes' => [],
            'price_min' => 0,
            'price_max' => $this->productService->getMaxPrice(),
            'on_sale' => false,
            'in_stock' => false,
        ];
        $this->resetPage();
    }

    public function setCategory($categoryId)
    {
        $this->filters['category'] = $categoryId == 0 ? null : $categoryId;
        $this->resetPage();
    }

    public function toggleFilter($type, $value)
    {
        switch ($type) {
            case 'brand':
                $this->toggleArrayValue('brands', $value);
                break;
            case 'color':
                $this->toggleArrayValue('colors', $value);
                break;
            case 'size':
                $this->toggleArrayValue('sizes', $value);
                break;
        }
    }

    private function toggleArrayValue($property, $value)
    {
        if (in_array($value, $this->filters[$property])) {
            $this->filters[$property] = array_diff($this->filters[$property], [$value]);
        } else {
            $this->filters[$property][] = $value;
        }
        $this->resetPage();
    }

    public function addToCart($productId, $variantId = null, $quantity = 1)
    {
        try {
            $this->cartStore->dispatch(CartActions::ADD_TO_CART, [
                'productId' => $productId,
                'variantId' => $variantId,
                'quantity' => $quantity
            ]);
            $this->dispatch('cart-updated');
            $this->dispatch('notify', [
                'message' => 'Item add to cart',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to add item: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        $products = $this->productService->getFilteredProducts(
            $this->filters,
            $this->search,
            $this->sortField,
            $this->sortDirection,
            $this->perPage
        );
        $filterOptions = $this->productService->getFilterOptions();

        return view('livewire.products.product-listing', [
            'products' => $products,
            'categories' => $filterOptions['categories'],
            'brands' => $filterOptions['brands'],
            'colors' => $filterOptions['colors'],
            'sizes' => $filterOptions['sizes'],
        ])->layout('layouts.app-layout');
    }
}
