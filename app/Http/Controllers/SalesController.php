<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sales = Sales::with('customer', 'items.product')->get();
        return view('pages.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'sales_code' => 'required|unique:sales,sales_code',
            'customer_id' => 'required|exists:customers,id',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'expiry_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0.01',
        ]);

        // Membuat Sales
        $sales = Sales::create([
            'sales_code' => $request->sales_code,
            'customer_id' => $request->customer_id,
            'billing_address' => $request->billing_address,
            'shipping_address' => $request->shipping_address,
            'expiry_date' => $request->expiry_date,
            'status' => 'sales_order',
        ]);

        // Menyimpan Item Sales
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $sales->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sales Order berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function confirmSales($id)
    {
        $sales = Sales::findOrFail($id);
        $sales->status = 'waiting_payment';
        $sales->save();

        return response()->json(['success' => true, 'message' => 'Sales Order berhasil dikirim']);
    }
    public function processPayment(Request $request, $id)
    {
        $sales = Sales::with('items')->findOrFail($id);

        if ($sales->status !== 'waiting_payment') {
            return response()->json(['error' => 'Status Sales Order belum valid untuk pembayaran!'], 400);
        }

        // Proses pembayaran
        $sales->status = 'done';
        $sales->save();

        // Mengupdate stok produk
        foreach ($sales->items as $item) {
            $product = $item->product;
            $product->stock -= $item->quantity; // Mengurangi stok
            $product->save();
        }

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil diproses']);
    }

    public function generateInvoice($id)
    {
        $sales = Sales::with('customer', 'items.product')->findOrFail($id);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.sales.invoice', compact('sales'));

        return $pdf->download('invoice-' . $sales->sales_code . '.pdf');
    }
}
