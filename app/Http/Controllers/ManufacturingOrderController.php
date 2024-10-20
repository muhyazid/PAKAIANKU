<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\ManufacturingOrder;

class ManufacturingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = ManufacturingOrder::with('product', 'materials')->get();
        return view('pages.manufacturing_orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
         $products = Product::all();
        $materials = Material::all();
        return view('pages.manufacturing_orders.create', compact('products', 'materials'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.to_consume' => 'required|numeric|min:0',
            'materials.*.unit' => 'required|string',
        ]);

        // Simpan manufacturing order
        $order = ManufacturingOrder::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'status' => 'Draft',
        ]);

        // Simpan material ke pivot table
        foreach ($request->materials as $material) {
            $order->materials()->attach($material['material_id'], [
                'to_consume' => $material['to_consume'],
                'unit' => $material['unit'],
            ]);
        }

        return redirect()->route('manufacturing-orders.index')->with('success', 'Manufacturing Order berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $order = ManufacturingOrder::with('materials')->findOrFail($id);
        return view('manufacturing_orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $order = ManufacturingOrder::with('materials')->findOrFail($id);
        $products = Product::all();
        $materials = Material::all();
        return view('manufacturing_orders.edit', compact('order', 'products', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.to_consume' => 'required|numeric|min:0',
            'materials.*.unit' => 'required|string',
        ]);

        // Update manufacturing order
        $order = ManufacturingOrder::findOrFail($id);
        $order->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'start_date' => $request->start_date,
            'status' => $request->status,
        ]);

        // Sync material ke pivot table
        $order->materials()->sync([]);
        foreach ($request->materials as $material) {
            $order->materials()->attach($material['material_id'], [
                'to_consume' => $material['to_consume'],
                'unit' => $material['unit']
            ]);
        }

        return redirect()->route('manufacturing-orders.index')->with('success', 'Manufacturing Order berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $order = ManufacturingOrder::findOrFail($id);
        $order->materials()->detach(); // Hapus relasi di tabel pivot
        $order->delete(); // Hapus order

        return redirect()->route('manufacturing-orders.index')->with('success', 'Manufacturing Order berhasil dihapus.');
    }
}
