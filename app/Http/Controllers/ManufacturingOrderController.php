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
   
    public function index()
    {
        //
        $orders = ManufacturingOrder::with('product')->get();
        return view('pages.manufacturing_orders.index', compact('orders'));
    }

    public function create()
    {
        $nextId = ManufacturingOrder::max('id') + 1; // Atau logika lain untuk mendapatkan ID berikutnya
        $kodeMO = 'MO-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $products = Product::all();
        $materials = Material::all();
        return view('pages.manufacturing_orders.create', compact('products', 'materials', 'kodeMO'));
    }

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

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
         $order = ManufacturingOrder::with('materials', 'product')->findOrFail($id);
        $products = Product::all();
        return view('pages.manufacturing_orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'kode_MO' => 'required|string|max:50',
                'quantity' => 'required|numeric|min:0.01',
                'start_date' => 'required|date',
                'materials' => 'required|array',
                'materials.*.material_id' => 'required|exists:materials,id',
                'materials.*.to_consume' => 'required|numeric|min:0'
            ]);

            $order = ManufacturingOrder::findOrFail($id);

            // Update the manufacturing order
            $order->update([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'start_date' => $validated['start_date']
                
            ]);

            // Sync materials
            $materialsData = [];
            foreach ($validated['materials'] as $materialId => $materialData) {
                $materialsData[$materialId] = [
                    'to_consume' => $materialData['to_consume'],
                    'quantity' => $materialData['to_consume']
                ];
            }

            // Sync the materials with the order
            $order->materials()->sync($materialsData);

            DB::commit();
            return redirect()->route('manufacturing_orders.index')
                        ->with('success', 'Manufacturing Order berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

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
        try {
            $order = ManufacturingOrder::findOrFail($id);
            $stockStatus = $order->checkMaterialStock();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $order->product->nama_produk,
                    'quantity' => $order->quantity,
                    'sufficient_materials' => $stockStatus['sufficient_materials'],
                    'insufficient_materials' => $stockStatus['insufficient_materials'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memeriksa stok.',
            ]);
        }
    }

    public function startProduction($id)
    {
        DB::beginTransaction(); // Tambahkan transaksi database
        try {
            $order = ManufacturingOrder::findOrFail($id);
            
            if ($order->status !== ManufacturingOrder::STATUS_DRAFT) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produksi sudah dimulai atau selesai.'
                ]);
            }

            $stockStatus = $order->checkMaterialStock();
            if (!$stockStatus['has_sufficient_stock']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi.'
                ]);
            }

            // Kurangi stok material
            $bom = BoM::where('product_id', $order->product_id)->first();
            foreach ($bom->materials as $material) {
                $requiredQuantity = $material->pivot->quantity * $order->quantity;
                $materialModel = Material::find($material->id);
                $materialModel->decrement('stock', $requiredQuantity);
            }

            $order->status = ManufacturingOrder::STATUS_CONFIRMED;
            $order->save();

            DB::commit(); // Commit transaksi

            return response()->json([
                'success' => true,
                'message' => 'Produksi dimulai.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function completeProduction($id)
    {
        try {
            $order = ManufacturingOrder::findOrFail($id);

            // Pastikan status sudah Confirmed
            if ($order->status !== 'Confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Produksi belum dimulai atau sudah selesai.'
                ]);
            }

            // Update status pesanan menjadi 'Done'
            $order->status = 'Done';
            $order->save();

            // Update stok produk berdasarkan jumlah yang diproduksi
            $order->product->stock += $order->quantity;
            $order->product->save();

            return response()->json([
                'success' => true,
                'message' => 'Produksi selesai dan stok diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

}
