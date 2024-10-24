<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoMController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ManufacturingOrderController;


Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

// Route::get('/produk', [ProductController::class, 'index']);
Route::resource('products', ProductController::class);

Route::resource('materials', MaterialController::class);

Route::resource('boms', BoMController::class);

Route::get('/boms/{id}/report', [BoMController::class, 'report'])->name('boms.report');

Route::resource('manufacturing_orders', ManufacturingOrderController::class);








