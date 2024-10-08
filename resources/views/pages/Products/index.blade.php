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
                <div class="card" style="border: 1px solid #000; padding: 10px; text-align: center;">
                    <!-- Gambar Produk -->
                    <div style="border: 1px solid #000; margin-bottom: 10px; height: 150px;">
                        <img class="card-img-top" src="{{ asset('storage/' . $product->image_path) }}"
                            alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <!-- Nama Produk -->
                    <h5 class="card-title" style="font-weight: bold;">{{ $product->nama_produk }}</h5>

                    <!-- Tombol Aksi -->
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
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            @include('pages.products.edit', ['product' => $product])
            @include('pages.products.view', ['product' => $product])
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
                        <div class="row">
                            <!-- Bagian Kiri: Upload Gambar -->
                            <div class="col-md-4 text-center">
                                <label for="image">Upload Gambar</label>
                                <div style="margin-bottom: 10px;">
                                    <img id="avatar-preview" src="#" alt="Gambar"
                                        style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover; display: block; margin: 0 auto;">
                                </div>
                                <input type="file" name="image" id="image" accept="image/*" style="display: none;"
                                    required>
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('image').click();">Browse...</button>
                                <span id="file-name" style="margin-left: 10px;">No file chosen</span>
                            </div>

                            <!-- Bagian Kanan: Input Form Lainnya -->
                            <div class="col-md-8">
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
                            </div>
                        </div>
                        <!-- Tombol Simpan -->
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Pratinjau Gambar -->
    <script>
        // JavaScript untuk menampilkan pratinjau gambar saat file dipilih
        document.getElementById('image').onchange = function(event) {
            const [file] = event.target.files; // Ambil file yang dipilih
            if (file) {
                const preview = document.getElementById('avatar-preview'); // Dapatkan elemen img pratinjau
                preview.src = URL.createObjectURL(file); // Tampilkan gambar yang dipilih di img
            }
        };
    </script>
@endsection
