@extends('layouts.master')

@section('title', 'Edit Sales')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Edit Sales</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="sales-form" action="{{ route('sales.update', $sales->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="sales_id" value="{{ $sales->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sales_code" class="text-light">Kode Sales</label>
                            <input type="text"
                                class="form-control subtotal-input @error('sales_code') is-invalid @enderror"
                                name="sales_code" value="{{ $sales->sales_code }}" readonly>
                            @error('sales_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_id" class="text-light">Customer</label>
                            <select name="customer_id" class="form-control @error('customer_id') is-invalid @enderror"
                                required>
                                <option value="">Pilih Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $sales->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiry_date" class="text-light">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                name="expiry_date" value="{{ \Carbon\Carbon::parse($sales->expiry_date)->format('Y-m-d') }}"
                                required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="billing_address" class="text-light">Alamat Penagihan</label>
                            <textarea name="billing_address" class="form-control @error('billing_address') is-invalid @enderror" required>{{ $sales->billing_address }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shipping_address" class="text-light">Alamat Pengiriman</label>
                            <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ $sales->shipping_address }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
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
                            @foreach ($sales->items as $index => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $index }}][product_id]"
                                            class="form-control item-select" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]"
                                            class="form-control quantity-input text-right" step="0.01" min="0.01"
                                            value="{{ $item->quantity }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][price]"
                                            class="form-control price-input text-right" value="{{ $item->price }}"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][subtotal]"
                                            class="form-control subtotal-input text-right" value="{{ $item->subtotal }}"
                                            readonly>
                                    </td>
                                    <td class="text-center">
                                        @if ($loop->first)
                                            <button type="button" class="btn btn-success btn-sm btn-add-row">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-row">-</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const materialsTable = document.getElementById('materials-table');
            const form = document.getElementById('sales-form');

            // Format mata uang
            function formatCurrency(number) {
                return parseFloat(number).toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Hitung subtotal untuk baris
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;

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
                    if (subtotalInput.id !== 'total-amount') {
                        total += parseFloat(subtotalInput.value.replace(/[^0-9.-]+/g, "")) || 0;
                    }
                });
                document.getElementById('total-amount').value = formatCurrency(total);
            }

            function addNewRow() {
                const newRow = materialsTable.querySelector('tbody tr').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                const newIndex = materialsTable.querySelectorAll('tbody tr').length;

                newRow.querySelectorAll('[name^="items[0]"]').forEach(element => {
                    element.name = element.name.replace('[0]', `[${newIndex}]`);
                });

                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.className = 'btn btn-danger btn-sm btn-delete-row';
                deleteButton.textContent = '-';
                deleteButton.onclick = function() {
                    newRow.remove();
                    updateTotalAmount();
                };

                const actionCell = newRow.querySelector('td:last-child');
                actionCell.innerHTML = '';
                actionCell.appendChild(deleteButton);

                materialsTable.querySelector('tbody').appendChild(newRow);
            }

            // Handle pemilihan item
            materialsTable.addEventListener('change', function(event) {
                if (event.target.classList.contains('item-select')) {
                    const row = event.target.closest('tr');
                    const priceInput = row.querySelector('.price-input');
                    const selectedOption = event.target.options[event.target.selectedIndex];

                    if (selectedOption.value) {
                        priceInput.value = selectedOption.dataset.price;
                        calculateSubtotal(row);
                    }
                }
            });

            // Update subtotal ketika quantity diubah
            materialsTable.addEventListener('input', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    calculateSubtotal(event.target.closest('tr'));
                }
            });

            materialsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-add-row')) {
                    addNewRow();
                } else if (event.target.classList.contains('btn-delete-row')) {
                    event.target.closest('tr').remove();
                    updateTotalAmount();
                }
            });

            // Inisialisasi subtotal dan total saat halaman dimuat
            materialsTable.querySelectorAll('tbody tr').forEach(row => {
                calculateSubtotal(row);
            });

            // Submit form
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Validasi minimal satu item
                const items = form.querySelectorAll('select[name^="items["][name$="[product_id]"]');
                if (items.length === 0 || !Array.from(items).some(select => select.value !== '')) {
                    alert("Silakan tambahkan minimal satu barang.");
                    return;
                }

                // Submit form menggunakan fetch
                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(JSON.stringify(errorData));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.location.href = '{{ route('sales.index') }}';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                    });
            });
        });
    </script>
@endsection
