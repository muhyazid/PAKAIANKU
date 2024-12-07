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
                                <td>{{ $sale->customer->nama_customer }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $sale->status === 'sales_order' ? 'warning' : ($sale->status === 'waiting_payment' ? 'info' : 'success') }}">
                                        {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($sale->status === 'waiting_payment')
                                        <button class="btn btn-sm btn-warning check-stock-btn"
                                            data-id="{{ $sale->id }}">Cek Stok</button>
                                    @endif
                                    @if ($sale->status === 'sales_order')
                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                            data-target="#modalConfirm{{ $sale->id }}">Kirim</button>
                                    @endif
                                    @if ($sale->status === 'waiting_payment')
                                        <a href="{{ route('sales.payment', $sale->id) }}"
                                            class="btn btn-sm btn-primary">Proses Pembayaran</a>
                                    @endif
                                    @if ($sale->status === 'done')
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#modalInvoice{{ $sale->id }}">Cetak Invoice</button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Konfirmasi Pengiriman (Kirim) -->
                            <!-- Modal untuk Konfirmasi Kirim -->
                            <div class="modal fade" id="modalConfirm{{ $sale->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="modalConfirmLabel{{ $sale->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pengiriman</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin mengirim order ini?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                            <a href="{{ route('sales.confirm', $sale->id) }}"
                                                class="btn btn-success">Kirim</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- modal cek stok --}}
                            <div class="modal fade" id="stockCheckModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cek Ketersediaan Stok</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Stock availability table will be dynamically inserted here -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Cetak Invoice -->
                            <div class="modal fade" id="modalInvoice{{ $sale->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="modalInvoiceLabel{{ $sale->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalInvoiceLabel{{ $sale->id }}">Invoice Sales
                                                Order</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Invoice untuk Sales Order ini siap dicetak.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                            <a href="{{ route('sales.generateInvoice', $sale->id) }}"
                                                class="btn btn-info">Cetak Invoice</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.check-stock-btn').on('click', function() {
                var salesId = $(this).data('id');
                $.ajax({
                    url: '/sales/' + salesId + '/check-stock',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var modalContent = '<table class="table">' +
                                '<thead><tr><th>Produk</th><th>Stok Tersedia</th><th>Jumlah Dibutuhkan</th><th>Status</th></tr></thead>' +
                                '<tbody>';

                            response.stock_availability.forEach(function(item) {
                                modalContent += '<tr>' +
                                    '<td>' + item.product_name + '</td>' +
                                    '<td>' + item.current_stock + '</td>' +
                                    '<td>' + item.requested_quantity + '</td>' +
                                    '<td>' + (item.is_available ? 'Tersedia' :
                                        'Tidak Tersedia') + '</td>' +
                                    '</tr>';
                            });

                            modalContent += '</tbody></table>';

                            if (response.is_all_available) {
                                modalContent +=
                                    '<div class="alert alert-success mt-3">Semua produk tersedia untuk dikirim.</div>';
                            } else {
                                modalContent +=
                                    '<div class="alert alert-danger mt-3">Beberapa produk tidak tersedia untuk dikirim.</div>';
                            }

                            $('#stockCheckModal .modal-body').html(modalContent);
                            $('#stockCheckModal').modal('show');
                        }
                    }
                });
            });
        });
    </script>
@endsection
