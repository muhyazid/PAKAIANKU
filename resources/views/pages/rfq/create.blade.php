@extends('layouts.master')

@section('title', 'Tambah RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Tambah Request for Quotation (RFQ)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('rfq.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="rfq_code" class="text-light">Kode RFQ</label>
                    <input type="text" class="form-control @error('rfq_code') is-invalid @enderror" name="rfq_code"
                        value="{{ $rfqCode }}" readonly>
                    @error('rfq_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="supplier_id" class="text-light">Supplier</label>
                    <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quotation_date" class="text-light">Tanggal Penawaran</label>
                    <input type="date" class="form-control @error('quotation_date') is-invalid @enderror"
                        name="quotation_date" value="{{ old('quotation_date') }}" required>
                    @error('quotation_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="text-light mt-4">Material yang Diminta</h4>
                <div class="table-responsive">
                    <table class="table table-dark" id="materials-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="materials[0][material_id]" class="form-control material-select" required>
                                        <option value="">Pilih Material</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}" data-price="{{ $material->price }}">
                                                {{ $material->nama_bahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="materials[0][quantity]" class="form-control quantity-input"
                                        step="0.01" required>
                                </td>
                                <td>
                                    <select name="materials[0][unit]" class="form-control" required>
                                        <option value="gram">gram</option>
                                        <option value="meter">meter</option>
                                        <option value="pcs">pcs</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="materials[0][material_price]"
                                        class="form-control price-input" value="0" readonly>
                                </td>
                                <td>
                                    <input type="text" name="materials[0][subtotal]" class="form-control subtotal-input"
                                        value="0" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-add-row">+</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('rfq.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const materialsTable = document.getElementById('materials-table');

                // Set event listener untuk dropdown material
                materialsTable.addEventListener('change', function(event) {
                    if (event.target.classList.contains('material-select')) {
                        const selectedOption = event.target.options[event.target.selectedIndex];
                        const price = selectedOption.dataset.price;
                        const row = event.target.closest('tr');
                        const priceInput = row.querySelector('.price-input');
                        const quantityInput = row.querySelector('.quantity-input');

                        priceInput.value = price || 0; // Set harga dari data-price
                        calculateSubtotal(row); // Hitung subtotal

                        // Pastikan subtotal diperbarui jika jumlah sudah diisi
                        if (quantityInput.value) {
                            calculateSubtotal(row);
                        }
                    }
                });

                // Set event listener untuk input jumlah
                materialsTable.addEventListener('input', function(event) {
                    if (event.target.classList.contains('quantity-input')) {
                        const row = event.target.closest('tr');
                        calculateSubtotal(row); // Hitung ulang subtotal saat jumlah berubah
                    }
                });

                // Fungsi untuk menghitung subtotal
                function calculateSubtotal(row) {
                    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const subtotalInput = row.querySelector('.subtotal-input');

                    subtotalInput.value = (quantity * price).toFixed(2); // Set nilai subtotal
                }
            });
        </script>
    @endpush
@endsection
