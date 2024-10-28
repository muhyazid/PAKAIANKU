<?php

namespace App\Http\Controllers;
use App\Models\BoM;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\ManufacturingOrder;
use Illuminate\Support\Facades\DB;

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
        $materials = Material::all();
        return view('pages.manufacturing_orders.create', compact('products', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'kode_MO' => 'required|string|max:50|unique:manufacturing_orders',
                'quantity' => 'required|numeric|min:0.01',
                'start_date' => 'required|date',
                'materials' => 'required|array',
                'materials.*.material_id' => 'required|exists:materials,id',
                'materials.*.to_consume' => 'required|numeric|min:0'
            ]);

            $order = ManufacturingOrder::create([
                'product_id' => $validated['product_id'],
                'kode_MO' => $validated['kode_MO'],
                'quantity' => $validated['quantity'],
                'start_date' => $validated['start_date'],
                'status' => ManufacturingOrder::STATUS_DRAFT
            ]);

            // Attach materials with their quantities
            foreach ($validated['materials'] as $materialId => $materialData) {
                $order->materials()->attach($materialId, [
                    'to_consume' => $materialData['to_consume'],
                    'quantity' => $materialData['to_consume']
                ]);
            }

            DB::commit();
            return redirect()->route('manufacturing_orders.index')
                           ->with('success', 'Manufacturing Order berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
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

    public function getMaterialsByProduct($productId)
    {
         try {
            $bom = BoM::where('product_id', $productId)
                     ->with(['materials' => function($query) {
                         $query->select('materials.*', 'bom_material.quantity', 'bom_material.unit');
                     }])
                     ->latest()
                     ->first();

            if (!$bom) {
                return response()->json([
                    'error' => 'Bill of Materials tidak ditemukan untuk produk ini'
                ], 404);
            }

            return response()->json([
                'materials' => $bom->materials
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data materials'
            ], 500);
        }
    }

    public function checkStock($id)
    {
        $order = ManufacturingOrder::with('materials', 'product')->findOrFail($id);
        $stockStatus = $order->checkMaterialStock();
        
        return response()->json($stockStatus);
    }

    // Method untuk memulai produksi
    public function startProduction($id)
    {
        try {
            $order = ManufacturingOrder::findOrFail($id);
            $order->startProduction();
            
            return response()->json([
                'success' => true,
                'message' => 'Produksi berhasil dimulai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
