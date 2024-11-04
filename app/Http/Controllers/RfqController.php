<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\RfqMaterial;
use App\Models\Suppliers;
use App\Models\Material;
use Illuminate\Http\Request;

class RfqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rfqs = Rfq::with('supplier')->get(); // Ambil data RFQ dengan relasi supplier
        return view('pages.rfq.index', compact('rfqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $suppliers = Suppliers::with('materials')->get(); // Ambil data supplier beserta material
        return view('pages.rfq.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'kode_rfq' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_penawaran' => 'required|date',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.spesifikasi' => 'required',
            'materials.*.satuan' => 'required',
            'materials.*.kuantitas' => 'required|numeric|min:0',
        ]);

        $rfq = Rfq::create($request->only(['kode_rfq', 'supplier_id', 'tanggal_penawaran']));

        foreach ($request->materials as $material) {
            RfqMaterial::create([
                'rfq_id' => $rfq->id,
                'material_id' => $material['material_id'],
                'spesifikasi' => $material['spesifikasi'],
                'satuan' => $material['satuan'],
                'kuantitas' => $material['kuantitas'],
            ]);
        }

        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rfq $rfq)
    {
        //
        $rfq->load('rfqMaterials.material');
        return view('pages.rfq.show', compact('rfq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rfq $rfq)
    {
        //
        $suppliers = Suppliers::with('materials')->get();
        $rfq->load('rfqMaterials');
        return view('pages.rfq.edit', compact('rfq', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, Rfq $rfq)
    {
        //
        $request->validate([
            'kode_rfq' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_penawaran' => 'required|date',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.spesifikasi' => 'required',
            'materials.*.satuan' => 'required',
            'materials.*.kuantitas' => 'required|numeric|min:0',
        ]);

        $rfq->update($request->only(['kode_rfq', 'supplier_id', 'tanggal_penawaran']));
        
        RfqMaterial::where('rfq_id', $rfq->id)->delete();

        foreach ($request->materials as $material) {
            RfqMaterial::create([
                'rfq_id' => $rfq->id,
                'material_id' => $material['material_id'],
                'spesifikasi' => $material['spesifikasi'],
                'satuan' => $material['satuan'],
                'kuantitas' => $material['kuantitas'],
            ]);
        }

        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rfq $rfq)
    {
        //
        $rfq->delete();
        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil dihapus!');
    }

    public function getMaterialsBySupplier($id)
    {
        $materials = Suppliers::find($id)->materials;
        return response()->json($materials);
    }

}
