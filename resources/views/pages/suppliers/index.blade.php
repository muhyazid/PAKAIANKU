@extends('layouts.master')

@section('title', 'Suppliers')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Suppliers</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Materials</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Materials</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card card-dark"> <!-- Menggunakan tema gelap untuk card -->
                <div class="card-body">
                    <h4 class="card-title text-white">Daftar Suppliers</h4>
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                        data-target="#addSuppliersModal">
                        Tambah Suppliers
                    </button>

                    <div class="table-responsive">
                        <table class="table table-bordered table-dark"> <!-- Gunakan tabel dark -->
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Suppliers</th>
                                    <th>No Phone</th>
                                    <th>Alamat</th>
                                    <th>Material yang disediakan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->nama }}</td>
                                        <td>{{ $supplier->no_tlp }}</td>
                                        <td>{{ $supplier->alamat }}</td>
                                        <td>{{ $supplier->material ? $supplier->material->nama_bahan : 'Tidak ada material' }}
                                        </td>
                                        <td>
                                            <!-- Tombol Aksi (View, Edit, Delete) -->
                                            <a href="#" class="btn btn-info btn-sm" title="View">View</a>
                                            <a href="#" class="btn btn-warning btn-sm" title="Edit">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSuppliersModal" tabindex="-1" role="dialog" aria-labelledby="addSuppliersModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSuppliersModalLabel">Tambah Suppliers Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('pages.suppliers.create')
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            const confirmation = confirm("Apakah kamu yakin ingin menghapus Suppliers ini?");
            if (!confirmation) {
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
@endsection
