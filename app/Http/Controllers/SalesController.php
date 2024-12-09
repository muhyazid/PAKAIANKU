<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesController extends Controller
{
 
    public function index()
    {
        $sales = Sales::all();
        $sales = Sales::with('customer', 'items.product')->get();
        $salesStatuses = Sales::STATUS;
        return view('pages.sales.index', compact('sales', 'salesStatuses'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        // Generate Sales Code
        $lastSales = Sales::latest()->first();
        $lastNumber = $lastSales ? intval(substr($lastSales->sales_code, 5)) : 0;
        $newNumber = $lastNumber + 1;
        $salesCode = 'SALES' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        
        // $items=[];
        return view('pages.sales.create', compact('customers', 'products', 'salesCode'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sales_code' => 'required|unique:sales,sales_code',
                'customer_id' => 'required|exists:customers,id',
                'billing_address' => 'required',
                'shipping_address' => 'required',
                'expiry_date' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.price' => 'required|numeric|min:0.01',
            ]);

            // Membuat Sales
            $sales = Sales::create([
                'sales_code' => $validatedData['sales_code'],
                'customer_id' => $validatedData['customer_id'],
                'billing_address' => $validatedData['billing_address'],
                'shipping_address' => $validatedData['shipping_address'],
                'expiry_date' => $validatedData['expiry_date'],
                'status' => 'sales_order',
            ]);

            // Menyimpan Item Sales
            foreach ($validatedData['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $sales->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
            }

            // Return JSON response for AJAX
            return response()->json([
                'success' => true, 
                'message' => 'Sales Order berhasil dibuat.',
                'redirect' => route('sales.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false, 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle other errors
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function confirmSales(Request $request, $id)
    {
        try {
            // Cari sales berdasarkan ID
            $sales = Sales::findOrFail($id);

           // Validasi status
            if ($sales->status !== Sales::STATUS['QUOTATION']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status sales order tidak valid untuk dikonfirmasi.'
                ], 400);
            }

            // Hitung total amount
            $totalAmount = $sales->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $sales->status = Sales::STATUS['SALES_ORDER'];
            $sales->total_amount = $totalAmount;
            $sales->save();


            return response()->json([
                'success' => true,
                'message' => 'Sales Order berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi sales: ' . $e->getMessage()
            ], 500);
        }
    }
    public function processPayment(Request $request, $id)
    {
        try {
            $sales = Sales::findOrFail($id);
            $paymentMethod = $request->input('payment_method');

             // Validasi metode pembayaran
            if (!in_array($paymentMethod, [
                Sales::PAYMENT_METHODS['CASH'], 
                Sales::PAYMENT_METHODS['TRANSFER']
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pembayaran tidak valid.'
                ], 400);
            }

            if ($sales->status !== Sales::STATUS['DELIVERED']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order tidak valid untuk pembayaran.'
                ], 400);
            }

            $sales->status = Sales::STATUS['DONE'];
            $sales->payment_method = $paymentMethod;
            $sales->save();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateInvoice($id)
    {
        $sales = Sales::with('customer', 'items.product')->findOrFail($id);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.sales.invoice', compact('sales'));

        return $pdf->download('invoice-' . $sales->sales_code . '.pdf');
    }

    public function checkStockAvailability($id)
    {
        try {
            $sales = Sales::with('items.product')->findOrFail($id);

            
            if ($sales->status !== Sales::STATUS['SALES_ORDER']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status sales order tidak valid untuk pengecekan stok.'
                ], 400);
            }

            $stockAvailability = [];
            $isAllAvailable = true;

            foreach ($sales->items as $item) {
                $product = $item->product;
                $requestedQuantity = $item->quantity;
                $currentStock = $product->stock;

                $availability = [
                    'product_name' => $product->nama_produk,
                    'requested_quantity' => $requestedQuantity,
                    'current_stock' => $currentStock,
                    'is_available' => $currentStock >= $requestedQuantity
                ];

                $stockAvailability[] = $availability;

                if ($currentStock < $requestedQuantity) {
                    $isAllAvailable = false;
                }
            }

            return response()->json([
                'success' => true,
                'is_all_available' => $isAllAvailable,
                'stock_availability' => $stockAvailability
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek stok: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deliverSales($id)
    {
        try {
            $sales = Sales::with('items')->findOrFail($id);

            if ($sales->status !== Sales::STATUS['SALES_ORDER']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status sales order tidak valid untuk dikirim.'
                ], 400);
            }

            // Kurangi stok produk
            foreach ($sales->items as $item) {
                $product = $item->product;
                if ($product->stock < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk {$product->nama_produk} tidak mencukupi."
                    ], 400);
                }
                $product->stock -= $item->quantity;
                $product->save();
            }

            $sales->status = Sales::STATUS['DELIVERED'];
            $sales->save();

            return response()->json([
                'success' => true,
                'message' => 'Sales Order berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim sales: ' . $e->getMessage()
            ], 500);
        }
    }
}
