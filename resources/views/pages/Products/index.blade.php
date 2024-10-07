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

    <!-- Tombol Tambah Produk -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahProdukModal">
        Tambah Produk
    </button>

    <!-- Grid Layout untuk Kartu Produk -->
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 18rem; text-align: center;">
                    <!-- Gambar Produk -->
                    <img class="card-img-top" src="{{ asset('storage/' . $product->image_path) }}"
                        alt="{{ $product->nama_produk }}" style="height: 200px; object-fit: cover;">
                    <!-- Nama Produk -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->nama_produk }}</h5>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="card-footer d-flex justify-content-around">
                        <!-- Tombol Edit -->
                        <button class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editProdukModal-{{ $product->id }}">
                            Edit
                        </button>
                        <!-- Tombol View -->
                        <button class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#viewProdukModal-{{ $product->id }}">
                            View
                        </button>
                        <!-- Tombol Delete -->
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Produk -->
            <div class="modal fade" id="editProdukModal-{{ $product->id }}" tabindex="-1"
                aria-labelledby="editProdukModalLabel-{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProdukModalLabel-{{ $product->id }}">Edit Produk -
                                {{ $product->nama_produk }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Edit Produk -->
                            <form action="{{ route('products.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="edit_nama_produk_{{ $product->id }}" class="form-label">Nama
                                        Produk</label>
                                    <input type="text" class="form-control" name="nama_produk"
                                        id="edit_nama_produk_{{ $product->id }}" value="{{ $product->nama_produk }}">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_kategori_{{ $product->id }}" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" name="kategori"
                                        id="edit_kategori_{{ $product->id }}" value="{{ $product->kategori }}">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_deskripsi_{{ $product->id }}" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi" id="edit_deskripsi_{{ $product->id }}">{{ $product->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_price_{{ $product->id }}" class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="price"
                                        id="edit_price_{{ $product->id }}" value="{{ $product->price }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal View Produk -->
            <div class="modal fade" id="viewProdukModal-{{ $product->id }}" tabindex="-1"
                aria-labelledby="viewProdukModalLabel-{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProdukModalLabel-{{ $product->id }}">View Produk -
                                {{ $product->nama_produk }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    alt="{{ $product->nama_produk }}" style="height: 200px; object-fit: cover;">
                                <p><strong>Nama Produk:</strong> {{ $product->nama_produk }}</p>
                                <p><strong>Kategori:</strong> {{ $product->kategori }}</p>
                                <p><strong>Deskripsi:</strong> {{ $product->deskripsi }}</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Produk -->
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" id="nama_produk">
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" id="kategori">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" class="form-control" name="price" id="price">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Produk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
