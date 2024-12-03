@extends('layouts.master')

@section('title', 'Daftar Sales Orders')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Sales Orders</h3>
        <div class="mb-3">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">Tambah Sales Order</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Kode Sales</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->sales_code }}</td>
                                <td>{{ $sale->customer->name }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $sale->status === 'sales_order' ? 'warning' : ($sale->status === 'waiting_payment' ? 'info' : 'success') }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('sales.confirm', $sale->id) }}"
                                        class="btn btn-sm btn-success">Kirim</a>
                                    @if ($sale->status === 'waiting_payment')
                                        <a href="{{ route('sales.payment', $sale->id) }}"
                                            class="btn btn-sm btn-primary">Proses Pembayaran</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
