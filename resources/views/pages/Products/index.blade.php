@extends('layouts.master')

@section('title', 'Produk')

@section('content')
    <!-- Tambahkan section header -->
    <div class="page-header">
        <h3 class="page-title"> Produk </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Produk</li>
            </ol>
        </nav>
    </div>
    <!-- Akhir section header -->

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Produk</h4>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                        data-target="#tambahProdukModal">
                        Tambah Produk
                    </button>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->nama_produk }}</td>
                                        <td>{{ $product->kategori }}</td>
                                        <td>{{ $product->deskripsi }}</td>
                                        <td>
                                            <!-- Tombol Edit yang memicu modal -->
                                            <a href="#" class="btn btn-warning" data-toggle="modal"
                                                data-target="#editProdukModal-{{ $product->id }}">Edit</a>
                                            <!-- Form Hapus -->
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>

                                            {{-- <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form> --}}
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Produk -->
                                    <!-- Modal Tambah Produk -->
                                    <div class="modal fade" id="tambahProdukModal" tabindex="-1" role="dialog"
                                        aria-labelledby="tambahProdukModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk Baru
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form Tambah Produk -->
                                                    <form action="{{ route('products.store') }}" method="POST">
                                                        @csrf
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <!-- Bagian Kiri -->
                                                                <div class="col-md-3">
                                                                    <!-- Placeholder untuk Gambar -->
                                                                    <div class="form-group">
                                                                        <label for="image">Gambar</label>
                                                                        <div
                                                                            style="width: 100px; height: 100px; border: 1px solid #ccc; background-color: #f8f8f8; margin-bottom: 15px;">
                                                                        </div>
                                                                    </div>
                                                                    <!-- Checkbox Options -->
                                                                    <div class="form-group">
                                                                        <label for="can_be_sold">
                                                                            <input type="checkbox" name="can_be_sold"
                                                                                id="can_be_sold"> Can be sold
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="can_be_purchased">
                                                                            <input type="checkbox" name="can_be_purchased"
                                                                                id="can_be_purchased"> Can be purchased
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <!-- Bagian Kanan -->
                                                                <div class="col-md-9">
                                                                    <!-- Product Name -->
                                                                    <div class="form-group">
                                                                        <label for="nama_produk">Product Name</label>
                                                                        <input type="text" name="nama_produk"
                                                                            class="form-control" id="nama_produk" required>
                                                                    </div>

                                                                    <!-- Product Type dan Barcode -->
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="product_type">Product Type</label>
                                                                            <input type="text" name="product_type"
                                                                                class="form-control" id="product_type">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="barcode">Barcode</label>
                                                                            <input type="text" name="barcode"
                                                                                class="form-control" id="barcode">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Category, Price, dan Cost -->
                                                                    <div class="form-group">
                                                                        <label for="kategori">Category</label>
                                                                        <input type="text" name="kategori"
                                                                            class="form-control" id="kategori" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="price">Price</label>
                                                                        <input type="number" name="price"
                                                                            class="form-control" id="price">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="cost">Cost</label>
                                                                        <input type="number" name="cost"
                                                                            class="form-control" id="cost">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
