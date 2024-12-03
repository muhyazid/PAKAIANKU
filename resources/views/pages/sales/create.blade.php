@extends('layouts.master')

@section('title', 'Tambah Sales')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Tambah Sales</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="sales-form" action="{{ route('sales.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="sales_code" class="text-light">Kode Sales</label>
                    <input type="text" class="form-control subtotal-input @error('sales_code') is-invalid @enderror"
                        name="sales_code" value="{{ $salesCode }}" readonly>
                    @error('sales_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_id" class="text-light">Customer</label>
                    <select name="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sales_date" class="text-light">Tanggal Penjualan</label>
                    <input type="date" class="form-control @error('sales_date') is-invalid @enderror" name="sales_date"
                        required>
                    @error('sales_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="text-light mt-4">Barang yang Dijual</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-bordered" id="materials-table">
                        <thead>
                            <tr>
                                <th class="text-center">Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="form-control item-select" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="products[0][quantity]"
                                        class="form-control quantity-input text-right" step="0.01" min="0.01"
                                        required>
                                </td>
                                <td>
                                    <input type="text" name="products[0][price]"
                                        class="form-control price-input text-right" readonly>
                                </td>
                                <td>
                                    <input type="text" name="products[0][subtotal]"
                                        class="form-control subtotal-input text-right" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-add-row">+</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td>
                                    <input type="text" id="total-amount" class="form-control subtotal-input text-right"
                                        readonly>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const materialsTable = document.getElementById('materials-table');

            // Format mata uang
            function formatCurrency(number) {
                return parseFloat(number).toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Update harga dan subtotal saat produk dipilih
            function handleItemSelect(select) {
                const row = select.closest('tr');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    const price = selectedOption.dataset.price;

                    // Update harga produk
                    const priceInput = row.querySelector('.price-input');
                    priceInput.value = price;

                    // Hitung subtotal jika quantity ada
                    const quantityInput = row.querySelector('.quantity-input');
                    if (quantityInput.value) {
                        calculateSubtotal(row);
                    }
                }
            }

            // Hitung subtotal untuk baris
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-input').value);
                const price = parseFloat(row.querySelector('.price-input').value);

                if (!quantity || !price) return;

                const subtotal = quantity * price;

                // Update subtotal input
                const subtotalInput = row.querySelector('.subtotal-input');
                subtotalInput.value = formatCurrency(subtotal);

                // Update total amount
                updateTotalAmount();
            }

            // Update total amount
            function updateTotalAmount() {
                let total = 0;
                materialsTable.querySelectorAll('.subtotal-input').forEach(subtotalInput => {
                    total += parseFloat(subtotalInput.value.replace(/[^0-9.-]+/g, "")) || 0;
                });
                document.getElementById('total-amount').value = formatCurrency(total);
            }

            // Event untuk menambahkan row
            materialsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-add-row')) {
                    const newRow = materialsTable.querySelector('tbody tr').cloneNode(true);
                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    newRow.querySelector('.btn-add-row').addEventListener('click', function() {
                        materialsTable.querySelector('tbody').appendChild(newRow);
                    });

                    // Append new row
                    materialsTable.querySelector('tbody').appendChild(newRow);
                }
            });

            // Update subtotal ketika quantity diubah
            materialsTable.addEventListener('input', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    calculateSubtotal(event.target.closest('tr'));
                }
            });

            // Handle pemilihan item
            materialsTable.addEventListener('change', function(event) {
                if (event.target.classList.contains('item-select')) {
                    handleItemSelect(event.target);
                }
            });

            // Submit form menggunakan AJAX
            const form = document.getElementById('sales-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Validasi agar ada item yang dipilih
                if (materialsTable.querySelectorAll('.item-select').length === 0) {
                    alert("Silakan tambahkan barang.");
                    return;
                }

                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('sales.index') }}'; // Redirect ke index
                        }
                    })
                    .catch(error => console.log(error));
            });
        });
    </script>
@endsection
