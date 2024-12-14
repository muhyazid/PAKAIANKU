@extends('layouts.master')

@section('title', 'Daftar Suppliers')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar Suppliers</h3>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSuppliersModal">
                Tambah Supplier
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Material yang Disediakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $index => $supplier)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $supplier->nama }}</td>
                                <td>{{ $supplier->no_tlp }}</td>
                                <td>{{ $supplier->alamat }}</td>
                                <td>{{ $supplier->material ? $supplier->material->nama_bahan : 'Tidak ada material' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewSupplierModal-{{ $supplier->id }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#editSupplierModal-{{ $supplier->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('pages.suppliers.create')
    @include('pages.suppliers.edit')
    @include('pages.suppliers.view')

@endsection
