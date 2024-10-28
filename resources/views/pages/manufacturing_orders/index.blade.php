<!-- Update index.blade.php -->
@extends('layouts.master')

@section('title', 'Daftar Manufacturing Orders')

@section('content')
    <h3>Daftar Manufacturing Orders</h3>
    <a href="{{ route('manufacturing_orders.create') }}" class="btn btn-primary">Tambah Manufacturing Order</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MO</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Mulai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->kode_MO }}</td>
                    <td>{{ $order->product->nama_produk }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->start_date }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        @if ($order->status === 'Draft')
                            <button class="btn btn-info btn-sm check-stock" data-id="{{ $order->id }}">
                                Cek Stock
                            </button>
                        @endif
                        <a href="{{ route('manufacturing_orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('manufacturing_orders.destroy', $order->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Cek Stock -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cek Ketersediaan Stock</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="stockStatus"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Dibutuhkan</th>
                                <th>Tersedia</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="stockTableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="startProductionBtn" style="display: none;">
                        Mulai Produksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let currentOrderId;

                $('.check-stock').click(function() {
                    currentOrderId = $(this).data('id');
                    checkStock(currentOrderId);
                });

                function checkStock(orderId) {
                    $.ajax({
                        url: `/manufacturing-orders/${orderId}/check-stock`,
                        method: 'GET',
                        success: function(response) {
                            updateStockModal(response);
                            $('#stockModal').modal('show');
                        },
                        error: function(xhr) {
                            alert('Error checking stock');
                        }
                    });
                }

                function updateStockModal(data) {
                    const tableBody = $('#stockTableBody');
                    tableBody.empty();

                    if (data.has_sufficient_stock) {
                        $('#stockStatus').html(
                            '<div class="alert alert-success">Semua material tersedia</div>'
                        );
                        $('#startProductionBtn').show();
                    } else {
                        $('#stockStatus').html(
                            '<div class="alert alert-danger">Beberapa material tidak mencukupi</div>'
                        );
                        $('#startProductionBtn').hide();
                    }

                    // Render sufficient materials
                    data.sufficient_materials.forEach(item => {
                        tableBody.append(`
                        <tr>
                            <td>${item.material.nama_bahan}</td>
                            <td>${item.required}</td>
                            <td>${item.available}</td>
                            <td><span class="badge badge-success">Tersedia</span></td>
                        </tr>
                    `);
                    });

                    // Render insufficient materials
                    data.insufficient_materials.forEach(item => {
                        tableBody.append(`
                        <tr>
                            <td>${item.material.nama_bahan}</td>
                            <td>${item.required}</td>
                            <td>${item.available}</td>
                            <td><span class="badge badge-danger">Kurang ${item.shortage}</span></td>
                        </tr>
                    `);
                    });
                }

                $('#startProductionBtn').click(function() {
                    if (!confirm('Yakin ingin memulai produksi?')) return;

                    $.ajax({
                        url: `/manufacturing-orders/${currentOrderId}/start-production`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#stockModal').modal('hide');
                            alert('Produksi berhasil dimulai');
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection



{{-- @extends('layouts.master')

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
                    <a href="{{ route('manufacturing_orders.create') }}" class="btn btn-primary mb-3">Tambah Manufacturing
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
                                    <th>Status</th>
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
                                        <td>{{ $order->status }}</td>
                                        <td>
                                            <a href="{{ route('manufacturing_orders.show', $order->id) }}"
                                                class="btn btn-info">View</a>
                                            <a href="{{ route('manufacturing_orders.edit', $order->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('manufacturing_orders.destroy', $order->id) }}"
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
@endsection --}}
