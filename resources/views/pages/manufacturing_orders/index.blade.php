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
    <div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Cek Ketersediaan Stok</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="stockStatus" class="mb-3"></div>
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
                        Lakukan Produksi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Button untuk cek status stok
        $(document).on('click', '.check-stock', function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '/manufacturing_orders/' + orderId + '/check-stock',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        var stockTableBody = $('#stockTableBody');
                        var stockStatus = $('#stockStatus');
                        var startProductionBtn = $('#startProductionBtn');
                        stockTableBody.empty();
                        stockStatus.empty();
                        var sufficientStock = true;

                        response.data.sufficient_materials.forEach(function(material) {
                            stockTableBody.append(`
                                <tr>
                                    <td>${material.material.nama_material}</td>
                                    <td>${material.required}</td>
                                    <td>${material.available}</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                </tr>
                            `);
                        });

                        response.data.insufficient_materials.forEach(function(material) {
                            stockTableBody.append(`
                            <tr>
                                <td>${material.material.nama_material}</td>
                                <td>${material.required}</td>
                                <td>${material.available}</td>
                                <td><i class="fas fa-times-circle text-danger"></i></td>
                            </tr>
                        `);
                            sufficientStock = false;
                        });

                        if (sufficientStock) {
                            stockStatus.html(
                                '<div class="alert alert-success">Stok material mencukupi untuk produksi.</div>'
                            );
                            startProductionBtn.show();
                        } else {
                            stockStatus.html(
                                '<div class="alert alert-danger">Stok material tidak mencukupi untuk produksi.</div>'
                            );
                            startProductionBtn.hide();
                        }
                    }
                }
            });
        });

        // Menangani klik tombol Lakukan Produksi
        $('#startProductionBtn').on('click', function() {
            var orderId = $('.check-stock').data('id');
            $.ajax({
                url: '/manufacturing_orders/' + orderId + '/start-production',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        alert('Produksi berhasil dimulai!');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
    </script>
@endsection
