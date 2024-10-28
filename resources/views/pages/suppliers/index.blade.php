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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $suppliers)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $suppliers->nama }}</td>
                                        <td>{{ $suppliers->no_tlp }}</td>
                                        <td>{{ $suppliers->alamat }}</td>
                                        {{-- <td>
                                            @if ($material->image)
                                                <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar Bahan"
                                                    width="50" class="rounded">
                                            @else
                                                Tidak ada gambar
                                            @endif
                                        </td> --}}
                                        <td>
                                            <!-- View Button -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#viewSuppliersModal-{{ $suppliers->id }}"
                                                class="btn btn-info btn-sm" title="View" style="margin-right: 5px;">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#editSuppliersModal-{{ $suppliers->id }}"
                                                class="btn btn-warning btn-sm" title="Edit" style="margin-right: 5px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('suppliers.destroy', $suppliers->id) }}" method="POST"
                                                style="display:inline-block;" onsubmit="return confirmDelete(event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal View Material -->
                                    @include('pages.suppliers.view', ['suppliers' => $suppliers])

                                    <!-- Modal Edit Material -->
                                    @include('pages.suppliers.edit', ['suppliers' => $suppliers])
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
