<?php

namespace App\Http\Controllers;

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
        $suppliers = Suppliers::orderBy('created_at', 'asc')->get();

        // Kirimkan variabel $materials ke view 'materials.index'
        return view('pages.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Tampilkan form tambah material
        return view('pages.suppliers.create');
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
        ]);

         // Handle upload gambar jika ada
        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('materials', 'public');
        // }

        // Simpan data material ke database
        Suppliers::create([
            'nama' => $request->nama,
            'no_tlp' => $request->no_tlp,
            'alamat' => $request->alamat,
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
        return view('pages.suppliers.edit', compact('suppliers'));
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
        ]);

        // Temukan data material
        $suppliers = Suppliers::findOrFail($id);

        // Handle upload gambar baru jika ada
        // if ($request->hasFile('image')) {
        //     // Hapus gambar lama jika ada
        //     if ($material->image) {
        //         Storage::delete('public/' . $material->image);
        //     }
        //     $imagePath = $request->file('image')->store('materials', 'public');
        //     $material->image = $imagePath;
        // }

        // Perbarui field lainnya
        $suppliers->nama = $request->nama;
        $suppliers->no_tlp = $request->no_tlp;
        $suppliers->alamat = $request->alamat;
        
        $suppliers->save();

        return redirect()->route('suppliers.index')->with('success', 'Suppliers berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus data material
        $suppliers = Suppliers::findOrFail($id);
        $suppliers->delete();
        return redirect()->route('suppliers.index')->with('success', 'Suppliers berhasil dihapus!');
    }
}
