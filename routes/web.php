<?php

use App\Livewire\Products\ProductListing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductListing::class)->name('products.index');
Route::get('test', function () {
    Redis::set('test_direct', 'Stored directly in Redis');
    return Redis::get('test_direct');
});
