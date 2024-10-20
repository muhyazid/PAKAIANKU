@extends('layouts.master')

@section('title', 'Manufacturing Orders')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Manufacturing Orders</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Manufacturing Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Manufacturing Orders</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Manufacturing Orders</h4>
                    <a href="{{ route('manufacturing-orders.create') }}" class="btn btn-primary mb-3">Tambah Manufacturing
                        Order</a>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <!-- Mengganti dari $manufacturingOrders menjadi $orders -->
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->product->nama_produk }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>{{ $order->start_date }}</td>
                                        <td>{{ $order->end_date }}</td>
                                        <td>
                                            <a href="{{ route('manufacturing-orders.show', $order->id) }}"
                                                class="btn btn-info">View</a>
                                            <a href="{{ route('manufacturing-orders.edit', $order->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('manufacturing-orders.destroy', $order->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ini?')">Delete</button>
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
