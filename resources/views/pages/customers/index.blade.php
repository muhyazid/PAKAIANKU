@extends('layouts.master')

@section('title', 'Customers')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Customers</h3>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                Tambah Customer
            </button>
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
                                        <!-- Tombol View -->
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewModal-{{ $customer->id }}">
                                            View
                                        </button>
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#editModal-{{ $customer->id }}">
                                            Edit
                                        </button>
                                        <!-- Tombol Delete -->
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

                            <!-- Modal View -->
                            <!-- Modal View Customer -->
                            <div class="modal fade" id="viewModal-{{ $customer->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="viewModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel">Detail Customer</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body stacked-info">
                                            <p><strong>Kode Customer:</strong> {{ $customer->kode_customer }}</p>
                                            <p><strong>Nama Customer:</strong> {{ $customer->nama_customer }}</p>
                                            <p><strong>Alamat:</strong> {{ $customer->alamat }}</p>
                                            <p><strong>No Telp:</strong> {{ $customer->no_telp }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal Edit -->
                            <!-- Modal Edit Customer -->
                            <div class="modal fade" id="editModal-{{ $customer->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="kode_customer">Kode Customer</label>
                                                    <input type="text"
                                                        class="form-control bg-dark text-white border-light"
                                                        id="kode_customer" name="kode_customer"
                                                        value="{{ $customer->kode_customer }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_customer">Nama Customer</label>
                                                    <input type="text"
                                                        class="form-control bg-dark text-white border-light"
                                                        id="nama_customer" name="nama_customer"
                                                        value="{{ $customer->nama_customer }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat">Alamat</label>
                                                    <textarea class="form-control bg-dark text-white border-light" id="alamat" name="alamat" required>{{ $customer->alamat }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="no_telp">No Telp</label>
                                                    <input type="number"
                                                        class="form-control bg-dark text-white border-light" id="no_telp"
                                                        name="no_telp" value="{{ $customer->no_telp }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create Customer -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Customer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_customer">Kode Customer</label>
                            <input type="text" class="form-control bg-dark text-white border-light" id="kode_customer"
                                name="kode_customer" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_customer">Nama Customer</label>
                            <input type="text" class="form-control bg-dark text-white border-light" id="nama_customer"
                                name="nama_customer" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control bg-dark text-white border-light" id="alamat" name="alamat" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telp</label>
                            <input type="number" class="form-control bg-dark text-white border-light" id="no_telp"
                                name="no_telp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
