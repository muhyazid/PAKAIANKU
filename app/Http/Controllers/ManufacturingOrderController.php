<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManufacturingOrder;
use App\Models\Product;

class ManufacturingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = ManufacturingOrder::with('product')->get();
        return view('pages.manufacturing_orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        return view ('pages.manufacturing_orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:Draft,Confirmed,Done',
        ]);

        ManufacturingOrder::create($request->only('product_id', 'quantity', 'start_date', 'end_date', 'status'));

        return redirect()->route('manufacturing_orders.index')->with('success', 'Manufacturing Order berhasil ditambahkan.');
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
        $order = ManufacturingOrder::findOrFail($id);
        $products = Product::all();
        return view('pages.manufacturing_orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:Draft,Confirmed,Done',
        ]);

        $order = ManufacturingOrder::findOrFail($id);
        $order->update($request->only('product_id', 'quantity', 'start_date', 'end_date', 'status'));

        return redirect()->route('manufacturing_orders.index')->with('success', 'Manufacturing Order berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $order = ManufacturingOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('manufacturing_orders.index')->with('success', 'Manufacturing Order berhasil dihapus.');
    }
}
