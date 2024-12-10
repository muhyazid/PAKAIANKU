@extends('layouts.master')

@section('title', 'Daftar Sales')
@php
    use App\Models\Sales; // Import model Sales di view
@endphp

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Daftar Sales</h3>
        <div class="mb-3">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">Tambah Sales</a>
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
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->sales_code }}</td>
                                <td>{{ $sale->customer->nama_customer ?? 'N/A' }}</td>
                                <td>
                                    <ul>
                                        @foreach ($sale->items as $item)
                                            <li class="product-item">
                                                <span class="product-name">{{ $item->product->nama_produk }} - </span>
                                                <span class="product-quantity">{{ $item->quantity }}
                                                    {{ $item->product->unit }} Unit</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $sale->status === Sales::STATUS['QUOTATION']
                                            ? 'warning'
                                            : ($sale->status === Sales::STATUS['SALES_ORDER']
                                                ? 'info'
                                                : ($sale->status === Sales::STATUS['DELIVERED']
                                                    ? 'primary'
                                                    : ($sale->status === Sales::STATUS['DONE']
                                                        ? 'success'
                                                        : 'secondary'))) }}">
                                        {{ ucwords(str_replace('_', ' ', $sale->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning mr-1">
                                            Edit
                                        </a>
                                        <button onclick="confirmDelete({{ $sale->id }})" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                        @switch($sale->status)
                                            @case(Sales::STATUS['QUOTATION'])
                                                <button onclick="confirmSales({{ $sale->id }})" class="btn btn-sm btn-success">
                                                    Konfirmasi
                                                </button>
                                            @break

                                            @case(Sales::STATUS['SALES_ORDER'])
                                                <button onclick="checkStock({{ $sale->id }})" class="btn btn-sm btn-info">
                                                    Cek Stok
                                                </button>
                                            @break

                                            @case(Sales::STATUS['DELIVERED'])
                                                <button onclick="showPaymentModal({{ $sale->id }})"
                                                    class="btn btn-sm btn-primary">
                                                    Bayar
                                                </button>
                                            @break

                                            @case(Sales::STATUS['DONE'])
                                                <a href="{{ route('sales.generateInvoice', $sale->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    Cetak Invoice
                                                </a>
                                            @break
                                        @endswitch
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Stock -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cek Ketersediaan Stok</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="stockModalBody">
                    <!-- Stok akan dimuat secara dinamis -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="selectedSalesId">
                    {{-- <button id="deliverButton" class="btn btn-success" style="display: none;"
                        onclick="deliverSales()">Kirim</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cashPayment"
                            value="cash">
                        <label class="form-check-label" for="cashPayment">Cash</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="transferPayment"
                            value="transfer">
                        <label class="form-check-label" for="transferPayment">Transfer</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="selectedSalesId">
                    <button class="btn btn-primary" onclick="processPayment()">Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSales(salesId) {
            $.ajax({
                url: `{{ route('sales.confirm', ['id' => ':id']) }}`.replace(':id', salesId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error untuk debugging
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }
            });
        }

        function checkStock(salesId) {
            $.ajax({
                url: `{{ route('sales.checkStock', ['id' => ':id']) }}`.replace(':id', salesId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let stockHtml = `
                            <div class="row mb-3">
                                <div class="col-4"><strong>Kode Sales</strong></div>
                                <div class="col-8">${response.sales_code}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4"><strong>Nama Sales</strong></div>
                                <div class="col-8">${response.sales_name}</div>
                            </div>
                            <table class="table table-bordered table-dark">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Jumlah Dibeli</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        let allAvailable = true;
                        response.stock_availability.forEach(item => {
                            const status = item.is_available ?
                                '<i class="fas fa-check-circle text-success"></i>' :
                                '<i class="fas fa-times-circle text-danger"></i>';

                            stockHtml += `
                                <tr>
                                    <td>${item.nama_produk}</td>
                                    <td>${item.current_stock}</td>
                                    <td>${item.requested_quantity}</td>
                                    <td class="text-center">${status}</td>
                                </tr>`;

                            if (!item.is_available) allAvailable = false;
                        });

                        stockHtml += `</tbody></table>`;

                        $('#stockModalBody').html(stockHtml);

                        // Logika tombol modal
                        const modalFooterHtml = `
                            <input type="hidden" id="selectedSalesId" value="${salesId}">
                            <button id="deliverButton" class="btn btn-success" style="display: ${allAvailable ? 'inline-block' : 'none'};" onclick="deliverSales(${salesId})">Kirim</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        `;

                        // Update footer modal
                        $('.modal-footer').html(modalFooterHtml);

                        $('#stockModal').modal('show');
                    } else {
                        alert(response.message || 'Gagal mengecek stok');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error untuk debugging
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }
            });
        }


        function deliverSales(salesId) {
            $.ajax({
                url: `{{ route('sales.deliver', ':id') }}`.replace(':id', salesId),
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message); // Tampilkan notifikasi "Sales berhasil dikirim"
                        $('#stockModal').modal('hide'); // Tutup modal
                        location.reload(); // Reload halaman
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error untuk debugging
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }
            });
        }

        function showPaymentModal(salesId) {
            $('#selectedSalesId').val(salesId);
            $('#paymentModal').modal('show');
        }

        function processPayment() {
            const salesId = $('#selectedSalesId').val();
            const paymentMethod = $('input[name="payment_method"]:checked').val();

            if (!paymentMethod) {
                alert('Pilih metode pembayaran');
                return;
            }

            $.ajax({
                url: `{{ route('sales.payment', ':id') }}`.replace(':id', salesId),
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_method: paymentMethod
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#paymentModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error untuk debugging
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }
            });
        }

        function confirmDelete(salesId) {
            if (confirm('Apakah Anda yakin ingin menghapus sales ini?')) {
                $.ajax({
                    url: `{{ route('sales.destroy', ':id') }}`.replace(':id', salesId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                    }
                });
            }
        }
    </script>
@endsection
