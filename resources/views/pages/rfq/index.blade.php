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
                                        class="badge badge-{{ $rfq->status === 'purchase_order'
                                            ? 'warning'
                                            : ($rfq->status === 'pay_to_bill'
                                                ? 'info'
                                                : ($rfq->status === 'rejected'
                                                    ? 'danger'
                                                    : 'success')) }}">
                                        {{ ucfirst($rfq->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <!-- tombol Edit -->
                                        <a href="{{ route('rfq.edit', $rfq->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <!-- tombol Delete -->
                                        <form action="{{ route('rfq.destroy', $rfq->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus RFQ ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                        @if ($rfq->status === 'purchase_order')
                                            <button type="button" class="btn btn-sm btn-success"
                                                onclick="showConfirmModal({{ $rfq->id }})">
                                                Konfirmasi
                                            </button>
                                        @elseif($rfq->status === 'pay_to_bill')
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPaymentModal({{ $rfq->id }})">
                                                Bayar
                                            </button>
                                        @elseif($rfq->status === 'done')
                                            <a href="{{ route('rfq.invoice', $rfq->id) }}" class="btn btn-sm btn-info">
                                                Cetak Invoice
                                            </a>
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

    <!-- Supplier Response Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi RFQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah RFQ ini diterima atau ditolak?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="confirmRfqId">
                    <button type="button" class="btn btn-success" onclick="confirmRfq('accepted')">Diterima</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRfq('rejected')">Ditolak</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Silakan pilih metode pembayaran:</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="paymentRfqId">
                    <button type="button" class="btn btn-primary" onclick="processPayment('cash')">
                        Cash
                    </button>
                    <button type="button" class="btn btn-info" onclick="processPayment('transfer')">
                        Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function showConfirmModal(rfqId) {
            $('#confirmRfqId').val(rfqId);
            $('#confirmModal').modal('show');
        }

        function confirmRfq(action) {
            const rfqId = $('#confirmRfqId').val();

            $.ajax({
                url: `/rfq/${rfqId}/confirm`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action
                },
                success: function(result) {
                    $('#confirmModal').modal('hide');
                    alert(result.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message || 'Terjadi kesalahan.');
                }
            });
        }

        function showPaymentModal(rfqId) {
            $('#paymentRfqId').val(rfqId);
            $('#paymentModal').modal('show');
        }

        function processPayment(method) {
            const rfqId = $('#paymentRfqId').val();

            $.ajax({
                url: `/rfq/${rfqId}/process-payment`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_method: method
                },
                success: function(result) {
                    $('#paymentModal').modal('hide');
                    alert(result.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message || 'Terjadi kesalahan.');
                }
            });
        }
    </script>


@endsection
