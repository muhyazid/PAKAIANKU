@extends('layouts.master')

@section('title', 'Daftar BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Bill of Materials (BoM)</h3>
        <div class="mb-3">
            <!-- Tombol Tambah BoM -->
            <a href="{{ route('boms.create') }}" class="btn btn-primary">Tambah BoM</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Kode Produksi</th>
                            <th>Nama Produk</th>
                            <th>Komponen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boms as $bom)
                            <tr>
                                <td>{{ $bom->production_code }}</td>
                                <td>{{ $bom->product->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                                <td>
                                    <ul>
                                        @foreach ($bom->materials as $material)
                                            <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                                                {{ $material->pivot->unit }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <!-- Tombol View -->
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#viewBoMModal-{{ $bom->id }}">
                                            View
                                        </button>
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('boms.destroy', $bom->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                        <!-- Tombol Report -->
                                        <a href="{{ route('boms.report', $bom->id) }}" class="btn btn-sm btn-primary">
                                            Cetak Report
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal View BoM -->
                            @include('pages.boms.view')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
