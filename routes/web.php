<?php

use App\Livewire\Products\ProductListing;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductListing::class)->name('products.index');
