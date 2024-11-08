<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\Material;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class RfqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rfqs = Rfq::with(['supplier', 'items.material'])->get();
        return view('pages.rfq.index', compact('rfqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $suppliers = Suppliers::all();
        $materials = Material::all();
        
        // Generate RFQ Code
        $lastRfq = Rfq::latest()->first();
        $lastNumber = $lastRfq ? intval(substr($lastRfq->rfq_code, 3)) : 0;
        $newNumber = $lastNumber + 1;
        $rfqCode = 'RFQ' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        
        return view('pages.rfq.create', compact('suppliers', 'materials', 'rfqCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'rfq_code' => 'required|unique:rfqs,rfq_code',
            'supplier_id' => 'required|exists:suppliers,id',
            'quotation_date' => 'required|date',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.01',
            'materials.*.unit' => 'required|string'
        ]);

        // Create RFQ
        $rfq = Rfq::create([
            'rfq_code' => $request->rfq_code,
            'supplier_id' => $request->supplier_id,
            'quotation_date' => $request->quotation_date,
            'status' => 'pending'
        ]);

        // Create RFQ Items
        foreach ($request->materials as $material) {
            $rfq->items()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
                'unit' => $material['unit']
            ]);
        }

        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil dibuat.');
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
        $rfq = Rfq::with('items')->findOrFail($id);
        $suppliers = Suppliers::all();
        $materials = Material::all();
        return view('pages.rfq.edit', compact('rfq', 'suppliers', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'rfq_code' => 'required|unique:rfqs,rfq_code,'.$id,
            'supplier_id' => 'required|exists:suppliers,id',
            'quotation_date' => 'required|date',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.01',
            'materials.*.unit' => 'required|string'
        ]);

        $rfq = Rfq::findOrFail($id);
        
        // Update RFQ
        $rfq->update([
            'rfq_code' => $request->rfq_code,
            'supplier_id' => $request->supplier_id,
            'quotation_date' => $request->quotation_date
        ]);

        // Delete existing items
        $rfq->items()->delete();

        // Create new items
        foreach ($request->materials as $material) {
            $rfq->items()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
                'unit' => $material['unit']
            ]);
        }

        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $rfq = Rfq::findOrFail($id);
        $rfq->items()->delete();
        $rfq->delete();
        return redirect()->route('rfq.index')->with('success', 'RFQ berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,confirmed'
        ]);

        $rfq = Rfq::findOrFail($id);
        $rfq->update(['status' => $request->status]);

        return redirect()->route('rfq.index')->with('success', 'Status RFQ berhasil diperbarui.');
    }

}

