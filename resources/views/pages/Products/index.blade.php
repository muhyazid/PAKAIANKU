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
                <div class="card">
                    <!-- Gambar Produk -->
                    <img class="card-img-top" src="path/to/your/image.jpg" alt="{{ $product->nama_produk }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->nama_produk }}</h5>
                        <p class="card-text">{{ $product->kategori }}</p>
                        <p class="card-text">{{ $product->deskripsi }}</p>
                        <!-- Harga Produk -->
                        @if (isset($product->price))
                            <p class="card-text">Harga: Rp {{ $product->price }}</p>
                        @endif
                        <!-- Tombol Edit dan Hapus -->
                        <a href="#" class="btn btn-warning" data-toggle="modal"
                            data-target="#editProdukModal-{{ $product->id }}">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Produk -->
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="nama_produk" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" name="kategori" class="form-control" id="kategori" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" name="price" class="form-control" id="price">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
