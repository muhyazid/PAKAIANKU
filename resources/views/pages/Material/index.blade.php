@extends('layouts.master')

@section('title', 'Materials')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar Materials</h3>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMaterialModal">
                Tambah Material
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
                            <th>Nama Bahan</th>
                            <th>Kuantitas</th>
                            <th>Satuan</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $index => $material)
                            <tr>
                                <td>{{ $index + 1 }}</td> <!-- Menggunakan nomor urut -->
                                <td>{{ $material->nama_bahan }}</td>
                                <td>{{ $material->kuantitas }}</td>
                                <td>{{ $material->satuan }}</td>
                                <td>{{ $material->stock }}</td>
                                <td>{{ number_format($material->price, 2, ',', '.') }}</td>
                                <td>
                                    @if ($material->image)
                                        <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar Bahan"
                                            width="50" class="rounded">
                                    @else
                                        Tidak ada gambar
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewMaterialModal-{{ $material->id }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#editMaterialModal-{{ $material->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST"
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
                            <div class="modal fade" id="viewMaterialModal-{{ $material->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewMaterialModalLabel">Detail Material</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center">
                                                @if ($material->image)
                                                    <img src="{{ asset('storage/' . $material->image) }}"
                                                        alt="{{ $material->nama_bahan }}" class="img-fluid rounded mb-3">
                                                @else
                                                    Tidak ada gambar
                                                @endif
                                            </div>
                                            <p><strong>Nama:</strong> {{ $material->nama_bahan }}</p>
                                            <p><strong>Kuantitas:</strong> {{ $material->kuantitas }}</p>
                                            <p><strong>Satuan:</strong> {{ $material->satuan }}</p>
                                            <p><strong>Stock:</strong> {{ $material->stock }}</p>
                                            <p><strong>Harga:</strong> {{ number_format($material->price, 2, ',', '.') }}
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
                            <div class="modal fade" id="editMaterialModal-{{ $material->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content bg-dark text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Material</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('materials.update', $material->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="nama_bahan">Nama Bahan</label>
                                                    <input type="text" class="form-control bg-dark text-white"
                                                        id="nama_bahan" name="nama_bahan"
                                                        value="{{ $material->nama_bahan }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kuantitas">Kuantitas</label>
                                                    <input type="number" class="form-control bg-dark text-white"
                                                        id="kuantitas" name="kuantitas" value="{{ $material->kuantitas }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="satuan">Satuan</label>
                                                    <input type="text" class="form-control bg-dark text-white"
                                                        id="satuan" name="satuan" value="{{ $material->satuan }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="stock">Stock</label>
                                                    <input type="number" class="form-control bg-dark text-white"
                                                        id="stock" name="stock" value="{{ $material->stock }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="number" class="form-control bg-dark text-white"
                                                        id="price" name="price" value="{{ $material->price }}"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image">Gambar</label>
                                                    <input type="file" class="form-control-file" id="image"
                                                        name="image">
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
@endsection
