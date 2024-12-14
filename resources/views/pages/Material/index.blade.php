@extends('layouts.master')

@section('title', 'Materials')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar Materials</h3>
        <div class="mb-3">
            <!-- Tombol untuk Menambah Material -->
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
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th>Kuantitas</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Harga</th>
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
                                            data-target="#viewMaterialModal-{{ $material->id }}">View</button>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#editMaterialModal-{{ $material->id }}">Edit</button>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @include('pages.material.create')
    @include('pages.material.edit')
    @include('pages.material.view')

@endsection
