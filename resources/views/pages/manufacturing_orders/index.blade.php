@extends ('layouts.master')

@section('title', 'Daftar Manufacturing Orders')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Manufacturing Orders</h3>
        <div class="mb-3">
            <a href="{{ route('manufacturing_orders.create') }}" class="btn btn-primary">Tambah Manufacturing Order</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Kode MO</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tanggal Mulai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->kode_MO }}</td>
                                <td>{{ $order->product->nama_produk }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ $order->start_date }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $order->status === 'Draft' ? 'warning' : ($order->status === 'Confirmed' ? 'info' : 'success') }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if ($order->status === 'Draft')
                                            <button class="btn btn-sm btn-info check-stock" data-id="{{ $order->id }}"
                                                data-toggle="modal" data-target="#stockModal">
                                                Cek Stock
                                            </button>
                                        @endif
                                        <a href="{{ route('manufacturing_orders.edit', $order->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('manufacturing_orders.destroy', $order->id) }}"
                                            method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Cek Stock -->
    <div class="modal fade" id="stockModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Cek Ketersediaan Stok</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="stockStatus"></div>
                    <table class="table table-dark table-bordered">
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

    <script>
        $(document).ready(function() {
            $('.check-stock').on('click', function() {
                const orderId = $(this).data('id');
                $.ajax({
                    url: `/manufacturing-orders/${orderId}/check-stock`,
                    method: 'GET',
                    beforeSend: function() {
                        $('#stockTableBody').html(
                            '<tr><td colspan="4" class="text-center">Loading...</td></tr>');
                    },
                    success: function(response) {
                        if (response.success) {
                            const {
                                sufficient_materials,
                                insufficient_materials,
                                has_sufficient_stock,
                                product,
                                quantity
                            } = response.data;

                            $('#stockStatus').html(
                                `<h5>${product}</h5><p>Jumlah: ${quantity}</p>`);
                            const tableBody = $('#stockTableBody').empty();

                            sufficient_materials.forEach(item => {
                                tableBody.append(`
                                    <tr>
                                        <td>${item.material.nama_bahan}</td>
                                        <td>${item.required}</td>
                                        <td>${item.available}</td>
                                        <td><span class="badge badge-success">Tersedia</span></td>
                                    </tr>
                                `);
                            });

                            insufficient_materials.forEach(item => {
                                tableBody.append(`
                                    <tr>
                                        <td>${item.material.nama_bahan}</td>
                                        <td>${item.required}</td>
                                        <td>${item.available}</td>
                                        <td><span class="badge badge-danger">Kurang ${item.shortage}</span></td>
                                    </tr>
                                `);
                            });

                            if (has_sufficient_stock) {
                                $('#startProductionBtn').show();
                            } else {
                                $('#startProductionBtn').hide();
                            }

                            $('#stockModal').modal('show');
                        }
                    },
                });
            });

            $('#startProductionBtn').on('click', function() {
                const orderId = $('.check-stock').data('id');
                if (confirm('Yakin ingin memulai produksi?')) {
                    $.ajax({
                        url: `/manufacturing-orders/${orderId}/start-production`,
                        method: 'POST',
                        success: function(response) {
                            alert(response.message);
                            if (response.success) {
                                location.reload();
                            }
                        },
                    });
                }
            });
        });
    </script>
@endsection
