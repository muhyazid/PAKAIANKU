<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        //
        $customers = Customer::all();
        return view('pages.customers.index', compact('customers')); //Mengirim data ke view
    }

    public function create()
    {
        return view('pages.customers.create');
    }

    //  // Menyimpan customer baru ke database
    public function store(Request $request)
    {
        //
        $request->validate([
            'kode_customer' => 'required|max:20|unique:customers,kode_customer',
            'nama_customer' => 'required|max:100',
            'alamat' => 'required',
            'no_telp' => 'required|numeric',
        ]);

        // membuat data baru berdasarkan input
        Customer::create($request->all());

         // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('customers.index')->with('succes', 'Customer berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id); // Mencari customer berdasarkan ID
        return view('pages.customers.index', compact('customer'));
    }

    // Mengupdate data customer yang ada
    public function update(Request $request, string $id)
    {
        //
        $request -> validate([
            'kode_customer' => 'required|max:20|unique:customers,kode_customer,' . $id,
            'nama_customer' => 'required|max:100',
            'alamat' => 'required',
            'no_telp' => 'required|numeric',
        ]);

        // Mencari customer berdasarkan ID dan update data
        $customer = Customer::findOrFail($id);
        $customer -> update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id); // Mencari customer berdasarkan ID
        $customer->delete(); // Menghapus data dari database

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
