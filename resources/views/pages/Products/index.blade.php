@extends('layouts.master')

@section('title', 'Produk')

@section('content')
    <!-- Section Header -->
    <div class="page-header">
        <h3 class="page-title"> Produk </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Produk</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahProdukModal">
        Tambah Produk
    </button>
    @include('pages.products.create')

    <!-- Grid Layout untuk Kartu Produk -->
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card" style="border: 1px solid #000; padding: 10px; text-align: center;">
                    <!-- Gambar Produk -->
                    <div style="border: 1px solid #000; margin-bottom: 10px; height: 150px;">
                        <img class="card-img-top" src="{{ asset('storage/' . $product->image_path) }}"
                            alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h5 class="card-title" style="font-weight: bold;">{{ $product->nama_produk }}</h5>
                    <p style="color: #f0f0f0; font-weight: bold;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div style="margin-top: 10px;">
                        <a href="#" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editProdukModal-{{ $product->id }}">
                            Edit
                        </a>
                        <a href="#" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#viewProdukModal-{{ $product->id }}">
                            View
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                            style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @include('pages.products.edit', ['product' => $product])
            @include('pages.products.view', ['product' => $product])
        @endforeach
    </div>
@endsection
