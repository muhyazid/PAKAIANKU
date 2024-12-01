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
                            <th>Nomor</th>
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

                            <!-- Modal View -->
                            <div class="modal fade" id="viewSupplierModal-{{ $supplier->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewSupplierModalLabel">Detail Supplier</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama Supplier:</strong> {{ $supplier->nama }}</p>
                                            <p><strong>No Telepon:</strong> {{ $supplier->no_tlp }}</p>
                                            <p><strong>Alamat:</strong> {{ $supplier->alamat }}</p>
                                            <p><strong>Material:</strong>
                                                {{ $supplier->material ? $supplier->material->nama_bahan : 'Tidak ada material' }}
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editSupplierModal-{{ $supplier->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Supplier</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="nama">Nama Supplier</label>
                                                    <input type="text" class="form-control bg-dark text-white"
                                                        id="nama" name="nama" value="{{ $supplier->nama }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="no_tlp">No Telepon</label>
                                                    <input type="text" class="form-control bg-dark text-white"
                                                        id="no_tlp" name="no_tlp" value="{{ $supplier->no_tlp }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat">Alamat</label>
                                                    <textarea class="form-control bg-dark text-white" id="alamat" name="alamat" required>{{ $supplier->alamat }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="material_id">Material</label>
                                                    <select class="form-control bg-dark text-white" id="material_id"
                                                        name="material_id">
                                                        <option value=""
                                                            {{ !$supplier->material ? 'selected' : '' }}>
                                                            Tidak ada material
                                                        </option>
                                                        @foreach ($materials as $material)
                                                            <option value="{{ $material->id }}"
                                                                {{ $supplier->material && $supplier->material->id == $material->id ? 'selected' : '' }}>
                                                                {{ $material->nama_bahan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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

    <!-- Modal Tambah Supplier -->
    <div class="modal fade" id="addSuppliersModal" tabindex="-1" role="dialog"
        aria-labelledby="addSuppliersModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSuppliersModalLabel">Tambah Supplier</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Supplier</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="no_tlp">No Telepon</label>
                            <input type="text" class="form-control" id="no_tlp" name="no_tlp" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="material_id">Material</label>
                            <select class="form-control" id="material_id" name="material_id">
                                <option value="">Pilih Material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
