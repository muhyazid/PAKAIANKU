<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoMController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\RfQController;

Route::resource('rfq', RfQController::class);


Route::get('/dashboard', function ()  {
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
Route::get('/manufacturing-orders/{id}/check-stock', [ManufacturingOrderController::class, 'checkStock']);
Route::post('/manufacturing-orders/{id}/complete-production', [ManufacturingOrderController::class, 'completeProduction']);
Route::post('/manufacturing-orders/{id}/start-production', [ManufacturingOrderController::class, 'startProduction']);



// Route resource untuk operasi CRUD RFQ
Route::resource('rfq', RfqController::class);
Route::patch('rfq/{id}/status', [RfqController::class, 'updateStatus'])->name('rfq.updateStatus');
Route::post('rfq/{id}/confirm', [RfqController::class, 'confirmRfq'])->name('rfq.confirm');
Route::post('rfq/{id}/process-payment', [RfqController::class, 'processPayment'])->name('rfq.processPayment');
Route::get('rfq/{id}/invoice', [RfqController::class, 'generateInvoice'])->name('rfq.invoice');

Route::get('/suppliers/{supplierId}/add-price', [SuppliersController::class, 'createMaterialPrice'])->name('suppliers.add_price');
Route::post('/suppliers/store-price', [SuppliersController::class, 'storeMaterialPrice'])->name('suppliers.store_price');

