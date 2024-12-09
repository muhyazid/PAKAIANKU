<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoMController;
use App\Http\Controllers\RfQController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ManufacturingOrderController;

Route::resource('rfq', RfQController::class);

// coba coba
Route::get('/dashboard', function ()  {
    return view('pages.dashboard');
});

// Route::get('/produk', [ProductController::class, 'index']);
Route::resource('products', ProductController::class);

Route::resource('materials', MaterialController::class);

Route::resource('suppliers', SuppliersController::class);

Route::resource('boms', BoMController::class);
Route::get('/boms/get-next-code', [BoMController::class, 'getNextBoMCode'])->name('boms.nextCode');
Route::get('/boms/{id}/report', [BoMController::class, 'report'])->name('boms.report');

Route::resource('manufacturing_orders', ManufacturingOrderController::class);
Route::get('/manufacturing_orders/materials/{productId}', [ManufacturingOrderController::class, 'getMaterialsByProduct']);
Route::get('/api/bom-materials/{productId}', [ManufacturingOrderController::class, 'getBomMaterials']);
Route::get('/manufacturing_orders/{id}/check-stock', [ManufacturingOrderController::class, 'checkStock']);
Route::post('/manufacturing_orders/{id}/complete-production', [ManufacturingOrderController::class, 'completeProduction']);
Route::post('/manufacturing_orders/{id}/start-production', [ManufacturingOrderController::class, 'startProduction']);

// Route resource untuk operasi CRUD RFQ
Route::resource('rfq', RfqController::class);
Route::patch('rfq/{id}/status', [RfqController::class, 'updateStatus'])->name('rfq.updateStatus');
Route::post('rfq/{id}/confirm', [RfqController::class, 'confirmRfq'])->name('rfq.confirm');
Route::post('rfq/{id}/process-payment', [RfqController::class, 'processPayment'])->name('rfq.processPayment');
Route::get('rfq/{id}/invoice', [RfqController::class, 'generateInvoice'])->name('rfq.invoice');

Route::get('/suppliers/{supplierId}/add-price', [SuppliersController::class, 'createMaterialPrice'])->name('suppliers.add_price');
Route::post('/suppliers/store-price', [SuppliersController::class, 'storeMaterialPrice'])->name('suppliers.store_price');

// Route Customers
Route::resource('customers', CustomerController::class);

Route::resource('sales', SalesController::class);
// Route::get('/sales/{id}/confirm', [SalesController::class, 'confirmSales'])->name('sales.confirm');
// Route::get('/sales/{id}/payment', [SalesController::class, 'processPayment'])->name('sales.payment');
// Route::get('/sales/{id}/generate-invoice', [SalesController::class, 'generateInvoice'])->name('sales.generateInvoice');
// Route::get('/sales/{id}/check-stock', [SalesController::class, 'checkStockAvailability'])->name('sales.checkStock');
// Route::get('/sales/{id}/deliver', [SalesController::class, 'deliverSales'])->name('sales.deliver');

Route::post('/sales/{id}/confirm', [SalesController::class, 'confirmSales'])->name('sales.confirm');
Route::post('/sales/{id}/payment', [SalesController::class, 'processPayment'])->name('sales.payment');
Route::get('/sales/{id}/generate-invoice', [SalesController::class, 'generateInvoice'])->name('sales.generateInvoice');
Route::get('/sales/{id}/check-stock', [SalesController::class, 'checkStockAvailability'])->name('sales.checkStock');
Route::post('/sales/{id}/deliver', [SalesController::class, 'deliverSales'])->name('sales.deliver');
