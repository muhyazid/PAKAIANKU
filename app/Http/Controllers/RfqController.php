<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\Material;
use Barryvdh\DomPDF\PDF;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;

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
        $materials = Material::select('id', 'nama_bahan', 'price')->get();
        
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

        ]);

        // Create RFQ
        $rfq = Rfq::create([
            'rfq_code' => $request->rfq_code,
            'supplier_id' => $request->supplier_id,
            'quotation_date' => $request->quotation_date,
            'status' => 'purchase_order',
        ]);

        // Tambahkan item RFQ
        foreach ($request->materials as $material) {
            $materialModel = Material::findOrFail($material['material_id']);
            $subtotal = $materialModel->price * $material['quantity'];
            // Simpan item RFQ
            $rfq->items()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
                'material_price' => $materialModel->price, // Simpan harga material
                'subtotal' => $subtotal              // Simpan subtotal
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
            'materials.*.quantity' => 'required|numeric|min:0.01'
        ]);

        $rfq = Rfq::findOrFail($id);
        
        // Update RFQ
        $rfq->update([
            'rfq_code' => $request->rfq_code,
            'supplier_id' => $request->supplier_id,
            'quotation_date' => $request->quotation_date
        ]);

         // Hapus item yang ada sebelumnya
        $rfq->items()->delete();

        foreach ($request->materials as $material) {
            $materialModel = Material::find($material['material_id']);
            $material_price = $materialModel->price;           // Ambil harga material
            $subtotal = $material_price * $material['quantity']; // Hitung subtotal

            // Simpan item RFQ
            $rfq->items()->create([
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
                'material_price' => $material_price, // Simpan harga material
                'subtotal' => $subtotal              // Simpan subtotal
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

    public function confirmRfq(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:accepted,rejected'
        ]);

        $rfq = Rfq::findOrFail($id);

        if ($request->action === 'accepted') {
            $rfq->status = 'pay_to_bill';
        } elseif ($request->action === 'rejected') {
            $rfq->status = 'rejected';
        }

        $rfq->save();

        return response()->json([
            'success' => true,
            'message' => 'RFQ berhasil dikonfirmasi.',
            'status' => $rfq->status
        ]);
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer',
        ]);

        $rfq = Rfq::with('items.material')->findOrFail($id);

        if ($rfq->status !== 'pay_to_bill') {
            return response()->json([
                'success' => false,
                'message' => 'Status RFQ belum valid untuk pembayaran!'
            ], 400);
        }

        // Hitung total
        $totalAmount = $rfq->items->sum('subtotal');

        // Rekam pembayaran
        PaymentRecord::create([
            'rfq_id' => $rfq->id,
            'amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_date' => now()
        ]);

        // Ubah status menjadi selesai
        $rfq->update([
            'status' => 'done',
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'payment_date' => now()
        ]);

        // Update stok material
        foreach ($rfq->items as $item) {
            $material = $item->material;
            $material->stock += $item->quantity;
            $material->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses!'
        ]);
    }


    public function generateInvoice($id)
    {
        $rfq = Rfq::with(['supplier', 'items.material'])->findOrFail($id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pages.rfq.invoice', compact('rfq'));
        
        return $pdf->download('invoice-' . $rfq->rfq_code . '.pdf');
    }

}

