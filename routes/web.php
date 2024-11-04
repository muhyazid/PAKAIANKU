<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoMController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\RfQController;

Route::resource('rfq', RfQController::class);


Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

// Route::get('/produk', [ProductController::class, 'index']);
Route::resource('products', ProductController::class);

Route::resource('materials', MaterialController::class);

Route::resource('suppliers', SuppliersController::class);

Route::resource('boms', BoMController::class);

Route::get('/boms/{id}/report', [BoMController::class, 'report'])->name('boms.report');

Route::resource('manufacturing_orders', ManufacturingOrderController::class);

Route::get('/manufacturing_orders/materials/{productId}', [ManufacturingOrderController::class, 'getMaterialsByProduct']);

Route::get('/api/bom-materials/{productId}', [ManufacturingOrderController::class, 'getBomMaterials']);

// routes/web.php
Route::get('/manufacturing-orders/{id}/check-stock', [ManufacturingOrderController::class, 'checkStock']);

Route::resource('rfq', RfqController::class);

Route::get('/supplier/{id}/materials', [RfqController::class, 'getMaterialsBySupplier']);
