@extends('layouts.master')

@section('title', 'Materials')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Materials </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Materials</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Materials</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Materials</h4>
                    <!-- Tombol untuk membuka modal -->
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMaterialModal">
                        Tambah Material
                    </button>
                    <!-- Tabel Material -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Bahan</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Stock</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $index => $material)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $material->nama_bahan }}</td>
                                        <td>{{ $material->kuantitas }}</td>
                                        <td>{{ $material->satuan }}</td>
                                        <td>{{ $material->stock }}</td>
                                        <td>
                                            @if ($material->image)
                                                <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar Bahan"
                                                    width="50">
                                            @else
                                                Tidak ada gambar
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Tombol View -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#viewMaterialModal-{{ $material->id }}"
                                                style="color: #6c757d; text-decoration: none; margin-right: 8px;">
                                                <i class="fas fa-eye" style="color: #6c757d;"></i> View
                                            </a>

                                            <!-- Tombol Edit -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#editMaterialModal-{{ $material->id }}"
                                                style="color: #ffc107; text-decoration: none; margin-right: 8px;">
                                                <i class="fas fa-edit" style="color: #ffc107;"></i> Edit
                                            </a>

                                            <!-- Form Hapus dengan konfirmasi -->
                                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="border: none; background: none; color: #dc3545; cursor: pointer; padding: 0; font-size: inherit;">
                                                    <i class="fas fa-trash" style="color: #dc3545;"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Include Modal View -->
                                    @include('pages.material.view', [
                                        'material' => $material,
                                    ])

                                    <!-- Modal Edit Material -->
                                    <div class="modal fade" id="editMaterialModal-{{ $material->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editMaterialModalLabel">Edit Material</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('materials.update', $material->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="nama_bahan">Nama Bahan</label>
                                                                        <input type="text" name="nama_bahan"
                                                                            class="form-control" id="nama_bahan"
                                                                            value="{{ $material->nama_bahan }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="kuantitas">Kuantitas</label>
                                                                        <input type="number" name="kuantitas"
                                                                            class="form-control" id="kuantitas"
                                                                            value="{{ $material->kuantitas }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="satuan">Satuan</label>
                                                                        <input type="text" name="satuan"
                                                                            class="form-control" id="satuan"
                                                                            value="{{ $material->satuan }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="stock">Stock</label>
                                                                        <input type="text" name="stock"
                                                                            class="form-control" id="stock"
                                                                            value="{{ $material->stock }}" required>
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

    <!-- Modal Tambah Material -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="addMaterialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMaterialModalLabel">Tambah Material Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('pages.material.create-form') <!-- Pastikan ini mengarah ke file yang benar -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            // Tampilkan dialog konfirmasi
            const confirmation = confirm("Apakah kamu yakin ingin menghapus material ini?");

            // Jika pengguna memilih "Tidak", batalkan submit form
            if (!confirmation) {
                event.preventDefault();
                return false;
            }

            // Jika "Ya", lanjutkan submit form
            return true;
        }
    </script>

@endsection
