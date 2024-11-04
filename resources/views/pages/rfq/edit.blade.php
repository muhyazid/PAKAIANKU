@extends('layouts.master')

@section('title', 'Edit RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Edit RFQ</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">RFQ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit RFQ</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit RFQ</h4>

                    <form action="{{ route('rfq.update', $rfq->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kode_rfq">Kode RFQ</label>
                            <input type="text" name="kode_rfq" id="kode_rfq" class="form-control"
                                value="{{ $rfq->kode_rfq }}" required>
                        </div>

                        <div class="form-group">
                            <label for="supplier_id">Pilih Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $rfq->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control"
                                value="{{ $rfq->tanggal }}" required>
                        </div>

                        <h5>Materials Required</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Spesifikasi</th>
                                    <th>Satuan</th>
                                    <th>Kuantitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rfq->materials as $material)
                                    <tr>
                                        <td>{{ $material->nama_bahan }}</td>
                                        <td><input type="text" name="materials[{{ $material->id }}][spesifikasi]"
                                                class="form-control" value="{{ $material->pivot->spesifikasi }}"></td>
                                        <td>{{ $material->satuan }}</td>
                                        <td><input type="number" name="materials[{{ $material->id }}][kuantitas]"
                                                class="form-control" value="{{ $material->pivot->kuantitas }}"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                        <a href="{{ route('rfq.index') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
