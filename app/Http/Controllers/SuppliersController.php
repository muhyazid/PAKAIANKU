<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data material dari database, urutkan berdasarkan ID atau created_at
        $suppliers = Suppliers::with('material')->orderBy('created_at', 'asc')->get();
        $materials = Material::all();
        // Kirimkan variabel $materials ke view 'materials.index'
        return view('pages.suppliers.index', compact('suppliers', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        // Tampilkan form tambah material
        return view('pages.suppliers.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Validasi field yang dibutuhkan
        $request->validate([
            'nama' => 'required|max:255',
            'no_tlp' => 'required||max:15',
            'alamat' => 'required',
            'material_id' => 'required|exists:materials,id',
        ]);


        // Simpan data material ke database
        Suppliers::create([
            'nama' => $request->nama,
            'no_tlp' => $request->no_tlp,
            'alamat' => $request->alamat,
            'material_id' => $request->material_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Suppliers berhasil ditambahkan!');
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
        // Ambil data material berdasarkan ID
        $suppliers = Suppliers::findOrFail($id);
        $materials = Material::all();
        return view('pages.suppliers.edit', compact('suppliers', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi field yang dibutuhkan
        $request->validate([
            'nama' => 'required|max:255',
            'no_tlp' => 'required||max:15',
            'alamat' => 'required',
            'material_id' => 'required|exists:materials,id',
        ]);

        // Temukan data material
        $supplier = Suppliers::findOrFail($id);

        $supplier->update([
            'nama' => $request->nama,
            'no_tlp' => $request->no_tlp,
            'alamat' => $request->alamat,
            'material_id' => $request->material_id,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Suppliers berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus data material
        $supplier = Suppliers::findOrFail($id);
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Suppliers berhasil dihapus!');
    }
}
