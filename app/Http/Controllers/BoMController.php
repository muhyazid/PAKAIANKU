<?php

namespace App\Http\Controllers;

use App\Models\BoM;
use App\Models\Product;
use App\Models\Material;
use App\Models\BoMMaterial;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BoMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Mengambil data BoM dengan relasi product dan materials
        $boms = BoM::with('product', 'materials')->get();
        return view('pages.boms.index', compact('boms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        $materials = Material::all();
        return view('pages.boms.create', compact('products', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'production_code' => 'required|string|unique:boms,production_code',
            'quantity' => 'required|integer|min:1',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit' => 'required|string',
        ]);

        // Simpan BoM ke database
        $bom = BoM::create([
            'product_id' => $request->product_id,
            'production_code' => $request->production_code,
            'quantity' => $request->quantity,
        ]);

         $totalBoMCost = 0;

        // Simpan material ke pivot table dan hitung biaya
        foreach ($request->materials as $material) {
            $materialData = Material::findOrFail($material['material_id']);
            $productCost = $materialData->price * $material['quantity'];
            $totalBoMCost += $productCost;

            // Simpan data material dan product cost
            $bom->materials()->attach($material['material_id'], [
                'quantity' => $material['quantity'],
                'unit' => $material['unit'],
                'product_cost' => $productCost
            ]);
        }

        return redirect()->route('boms.index')->with('success', 'BoM berhasil ditambahkan.');
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
        $bom = BoM::with('materials')->findOrFail($id);
        $products = Product::all();
        $materials = Material::all();
        return view('pages.boms.edit', compact('bom', 'products', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $request->validate([
            'product_id' => 'required|exists:products,id',
            'production_code' => 'required|string|unique:boms,production_code,' . $id,
            'quantity' => 'required|integer|min:1',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit' => 'required|string',
        ]);

        // Update BoM
        $bom = BoM::findOrFail($id);
        $bom->update([
            'product_id' => $request->product_id,
            'production_code' => $request->production_code,
            'quantity' => $request->quantity,
        ]);

        // Sync material ke pivot table
        $bom->materials()->sync([]);
        foreach ($request->materials as $material) {
            $bom->materials()->attach($material['material_id'], [
                'quantity' => $material['quantity'],
                'unit' => $material['unit']
            ]);
        }

        return redirect()->route('boms.index')->with('success', 'BoM berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bom = BoM::findOrFail($id);
        $bom->materials()->detach(); // Hapus relasi di tabel pivot
        $bom->delete(); // Hapus BoM

        return redirect()->route('boms.index')->with('success', 'BoM berhasil dihapus.');
    }

    // Method untuk menghasilkan PDF Report
    public function report($id)
    {
        
        $bom = BoM::with('materials', 'product')->findOrFail($id);

        // Menghitung total biaya BoM
        $totalBoMCost = 0;
        foreach ($bom->materials as $material) {
            $productCost = $material->price * $material->pivot->quantity;
            $totalBoMCost += $productCost;
        }

        // Generate PDF menggunakan domPDF
        $pdf = Pdf::loadView('pages.boms.report', compact('bom', 'totalBoMCost'));

        // Unduh file PDF
        return $pdf->download('BoM_Report_' . $bom->production_code . '.pdf');
    }
}
