<?php

namespace App\Http\Controllers;

use App\Models\BoM;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\ManufacturingOrder;

class ManufacturingOrderController extends Controller
{
    public function index()
    {
        $orders = ManufacturingOrder::with('product', 'materials')->get();
        return view('pages.manufacturing_orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('pages.manufacturing_orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:Draft,Confirmed,Done',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.to_consume' => 'required|numeric|min:0',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.consumed' => 'nullable|boolean',
        ]);

        // Simpan manufacturing order
        $order = ManufacturingOrder::create($request->only('product_id', 'quantity', 'start_date', 'end_date', 'status'));

        // Simpan material terkait
        $materialData = [];
        foreach ($request->materials as $material) {
            $materialData[$material['material_id']] = [
                'to_consume' => $material['to_consume'],
                'quantity' => $material['quantity'],
                'consumed' => isset($material['consumed']) ? 1 : 0,
            ];
        }

        // Sync material dengan pivot table
        $order->materials()->sync($materialData);

        return redirect()->route('manufacturing_orders.index')->with('success', 'Manufacturing Order berhasil ditambahkan.');
    }


    public function getMaterialsByProduct($productId)
    {
        $bom = BoM::with('materials')->where('product_id', $productId)->first();

        if (!$bom) {
            return response()->json(['error' => 'BoM tidak ditemukan'], 404);
        }

        return response()->json([
            'materials' => $bom->materials
        ]);
    }
}
