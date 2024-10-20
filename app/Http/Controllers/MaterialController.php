<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data material dari database, urutkan berdasarkan ID atau created_at
        $materials = Material::orderBy('created_at', 'asc')->get();

        // Kirimkan variabel $materials ke view 'materials.index'
        return view('pages.material.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Tampilkan form tambah material
        return view('pages.material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Validasi field yang dibutuhkan
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kuantitas' => 'required|numeric',
            'satuan' => 'required|string|max:50',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

         // Handle upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public');
        }

        // Simpan data material ke database
        Material::create([
            'nama_bahan' => $request->nama_bahan,
            'kuantitas' => $request->kuantitas,
            'satuan' => $request->satuan,
            'price' => $request->price, 
            'image' => $imagePath,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material berhasil ditambahkan!');
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
        $material = Material::findOrFail($id);
        return view('pages.material.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi field yang dibutuhkan
        $request->validate([
        'nama_bahan' => 'required|string|max:255',
        'kuantitas' => 'required|numeric',
        'satuan' => 'required|string|max:50',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Temukan data material
        $material = Material::findOrFail($id);

        // Handle upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($material->image) {
                Storage::delete('public/' . $material->image);
            }
            $imagePath = $request->file('image')->store('materials', 'public');
            $material->image = $imagePath;
        }

        // Perbarui field lainnya
        $material->nama_bahan = $request->nama_bahan;
        $material->kuantitas = $request->kuantitas;
        $material->satuan = $request->satuan;
        $material->price = $request->price;
        
        $material->save();

        return redirect()->route('materials.index')->with('success', 'Material berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus data material
        $material = Material::findOrFail($id);
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material berhasil dihapus!');
    }
}
