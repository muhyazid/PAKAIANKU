<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class SalesController extends Controller
{
 
    public function index()
    {
        //
        $sales = Sales::with('customer', 'items.product')->get();
        return view('pages.sales.index', compact('sales'));
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

    public function confirmSales($id)
    {
        try {
            // Cari sales berdasarkan ID
            $sales = Sales::findOrFail($id);

            // Pastikan status saat ini adalah 'sales_order'
            if ($sales->status !== 'quotation') {
                return redirect()->route('sales.index')
                    ->with('error', 'Status sales order tidak valid untuk dikonfirmasi.');
            }
            // Ubah status menjadi 'waiting_payment'
            $sales->status = 'sales_order';
            $sales->save();

            // Redirect dengan pesan sukses
            return redirect()->route('sales.index')
                ->with('success', 'Sales Order berhasil dikirim.');
        } catch (\Exception $e) {
            // Tangani error
            return redirect()->route('sales.index')
                ->with('error', 'Gagal mengkonfirmasi sales: ' . $e->getMessage());
        }
    }
    public function processPayment(Request $request, $id)
    {
        try {
            $sales = Sales::findOrFail($id);

            if ($sales->status !== 'delivered') {
                return redirect()->route('sales.index')
                    ->with('error', 'Sales Order tidak valid untuk pembayaran.');
            }

            $sales->status = 'done';
            $sales->save();

            return redirect()->route('sales.index')
                ->with('success', 'Pembayaran berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
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

            // Pastikan status saat ini adalah 'waiting_payment'
            if ($sales->status !== 'sales_order') {
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
                    'product_name' => $product->name,
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

            // Pastikan status saat ini adalah 'waiting_payment'
            if ($sales->status !== 'sales_order') {
                return redirect()->route('sales.index')
                    ->with('error', 'Status sales order tidak valid untuk dikirim.');
            }

            // Kurangi stok produk
            foreach ($sales->items as $item) {
                $product = $item->product;
                if ($product->stock < $item->quantity) {
                    return redirect()->route('sales.index')
                        ->with('error', 'Stok produk tidak mencukupi untuk pengiriman.');
                }
                $product->stock -= $item->quantity;
                $product->save();
            }

            // Ubah status menjadi 'delivered'
            $sales->status = 'delivered';
            $sales->save();

            return redirect()->route('sales.index')
                ->with('success', 'Sales Order berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')
                ->with('error', 'Gagal mengirim sales: ' . $e->getMessage());
        }
    }
}
