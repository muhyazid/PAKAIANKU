@extends('layouts.master')

@section('title', 'Daftar RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Request for Quotation (RFQ)</h3>
        <div class="mb-3">
            <a href="{{ route('rfq.create') }}" class="btn btn-primary">Tambah RFQ</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Kode RFQ</th>
                            <th>Supplier</th>
                            <th>Tanggal Penawaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rfqs as $rfq)
                            <tr>
                                <td>{{ $rfq->rfq_code }}</td>
                                <td>{{ $rfq->supplier->nama }}</td>
                                <td>{{ $rfq->quotation_date->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $rfq->status === 'pending' ? 'warning' : ($rfq->status === 'approved' ? 'success' : 'info') }}">
                                        {{ ucfirst($rfq->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('rfq.edit', $rfq->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('rfq.destroy', $rfq->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                        @if ($rfq->status === 'pending')
                                            <form action="{{ route('rfq.updateStatus', $rfq->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
