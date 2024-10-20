@extends('layouts.master')

@section('title', 'Daftar BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar Bill of Materials (BoM)</h3>
        <a href="{{ route('boms.create') }}" class="btn btn-primary">Tambah BoM</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Kode Produksi</th>
                            <th>Jumlah Produksi</th>
                            <th>Komponen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boms as $bom)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bom->product->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                                <td>{{ $bom->production_code }}</td>
                                <td>{{ $bom->quantity }}</td>
                                <td>
                                    <ul>
                                        @foreach ($bom->materials as $material)
                                            <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                                                {{ $material->pivot->unit }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#viewBoMModal-{{ $bom->id }}" title="View">
                                        <i class="fas fa-eye"></i> View
                                    </button>

                                    <!-- Edit Button -->
                                    <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Report Button -->
                                    <a href="{{ route('boms.report', $bom->id) }}" class="btn btn-success"
                                        title="Download Report">
                                        <i class="fas fa-file-pdf"></i> Report
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('boms.destroy', $bom->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete"
                                            onclick="return confirm('Apakah kamu yakin ingin menghapus BoM ini?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal for Viewing BoM Details -->
                            <div class="modal fade" id="viewBoMModal-{{ $bom->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="viewBoMModalLabel-{{ $bom->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content text-light" style="background-color: #343a40;">
                                        <!-- Tambahkan text-light -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewBoMModalLabel-{{ $bom->id }}">BoM Details
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" class="text-light">&times;</span>
                                                <!-- Tambahkan text-light -->
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Nama Produk: {{ $bom->nama_produk }}</h6>
                                            <p>Kode Produksi: {{ $bom->production_code }}</p>
                                            <p>Jumlah Produksi: {{ $bom->quantity }}</p>

                                            <h6>Komponen:</h6>
                                            <ul>
                                                @foreach ($bom->materials as $material)
                                                    <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                                                        {{ $material->pivot->unit }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
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
@endsection
