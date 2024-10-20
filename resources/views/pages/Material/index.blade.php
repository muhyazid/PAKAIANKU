@extends('layouts.master')

@section('title', 'Materials')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Materials</h3>
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
                    <h4 class="card-title text-white">Daftar Materials</h4>
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMaterialModal">
                        Tambah Material
                    </button>

                    <div class="table-responsive">
                        <table class="table table-bordered table-dark"> <!-- Gunakan tabel dark -->
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Bahan</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Price</th>
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
                                        <td>{{ $material->price }}</td>
                                        <td>
                                            @if ($material->image)
                                                <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar Bahan"
                                                    width="50" class="rounded">
                                            @else
                                                Tidak ada gambar
                                            @endif
                                        </td>
                                        <td>
                                            <!-- View Button -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#viewMaterialModal-{{ $material->id }}"
                                                class="btn btn-info btn-sm" title="View" style="margin-right: 5px;">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="#" data-toggle="modal"
                                                data-target="#editMaterialModal-{{ $material->id }}"
                                                class="btn btn-warning btn-sm" title="Edit" style="margin-right: 5px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST"
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
                                    @include('pages.material.view', ['material' => $material])

                                    <!-- Modal Edit Material -->
                                    @include('pages.material.edit', ['material' => $material])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    @include('pages.material.create-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            const confirmation = confirm("Apakah kamu yakin ingin menghapus material ini?");
            if (!confirmation) {
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
@endsection
