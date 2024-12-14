@extends('layouts.master')

@section('title', 'Customers')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Customers</h3>
        <div class="mb-3">
            <!-- Tombol untuk memanggil modal create -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                Tambah Customer
            </button>
            <!-- Include modal create -->
            @include('pages.customers.create')
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>No Telp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->kode_customer }}</td>
                                <td>{{ $customer->nama_customer }}</td>
                                <td>{{ $customer->alamat }}</td>
                                <td>{{ $customer->no_telp }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewModal-{{ $customer->id }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#editModal-{{ $customer->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @include('pages.customers.view', ['customer' => $customer])
                            @include('pages.customers.edit', ['customer' => $customer])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
