@extends('layouts.master')

@section('title', 'Edit Manufacturing Order')

@section('content')
    <h3>Edit Manufacturing Order</h3>

    <form action="{{ route('manufacturing_orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">Produk</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $order->product_id ? 'selected' : '' }}>
                        {{ $product->nama_produk }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kode_MO">Kode MO</label>
            <input type="text" name="kode_MO" class="form-control" value="{{ $order->kode_MO }}" required>
        </div>

        <div class="form-group">
            <label for="quantity">Kuantitas</label>
            <input type="number" name="quantity" class="form-control" value="{{ $order->quantity }}" required>
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" class="form-control"
                value="{{ date('Y-m-d\TH:i', strtotime($order->start_date)) }}" required>
        </div>

        <!-- Tombol Cek Stok -->
        <div class="form-group">
            <button type="button" class="btn btn-info" id="check-stock-btn" data-id="{{ $order->id }}">Cek Stok</button>
        </div>

        <!-- Modal Cek Stok -->
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

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('manufacturing_orders.index') }}" class="btn btn-secondary">Batal</a>
    </form>

@endsection

@section('scripts')
    <script>
        // Button untuk cek status stok
        $(document).on('click', '#check-stock-btn', function() {
            var orderId = $(this).data('id');

            // Panggil AJAX untuk memeriksa status stok
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
                                <td>${material.material.name}</td>
                                <td>${material.required}</td>
                                <td>${material.available}</td>
                                <td><i class="fas fa-check-circle text-success"></i></td>
                            </tr>
                        `);
                        });

                        response.data.insufficient_materials.forEach(function(material) {
                            stockTableBody.append(`
                            <tr>
                                <td>${material.material.name}</td>
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

                        $('#stockModal').modal('show');
                    }
                }
            });
        });

        // Menangani klik tombol Lakukan Produksi
        $('#startProductionBtn').on('click', function() {
            var orderId = $('#check-stock-btn').data('id');

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
