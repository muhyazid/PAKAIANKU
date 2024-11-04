@extends('layouts.master')

@section('title', 'Daftar RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar RFQ</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">RFQ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar RFQ</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card card-dark">
                <div class="card-body">
                    <h4 class="card-title text-white">Daftar RFQ</h4>
                    <a href="{{ route('rfq.create') }}" class="btn btn-primary mb-3">Tambah RFQ</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-dark">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kode RFQ</th>
                                    <th>Nama Supplier</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rfqs as $rfq)
                                    <tr>
                                        <td>{{ $rfq->id }}</td>
                                        <td>{{ $rfq->kode_rfq }}</td>
                                        <td>{{ $rfq->supplier->nama }}</td>
                                        <td>{{ $rfq->tanggal }}</td>
                                        <td>
                                            <a href="{{ route('rfq.show', $rfq->id) }}"
                                                class="btn btn-info btn-sm">Lihat</a>
                                            <a href="{{ route('rfq.edit', $rfq->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('rfq.destroy', $rfq->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
