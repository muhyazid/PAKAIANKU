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
                    <input type="text" class="form-control subtotal-input @error('rfq_code') is-invalid @enderror"
                        name="rfq_code" value="{{ $rfqCode }}" readonly>
                    @error('rfq_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="supplier_id" class="text-light">Supplier</label>
                    <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quotation_date" class="text-light">Tanggal Penawaran</label>
                    <input type="date" class="form-control @error('quotation_date') is-invalid @enderror"
                        name="quotation_date" required>
                    @error('quotation_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="text-light mt-4">Material yang Diminta</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-bordered" id="materials-table">
                        <thead>
                            <tr>
                                <th class="text-center">Material</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Aksi</th>
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
                                    <input type="number" name="materials[0][quantity]"
                                        class="form-control quantity-input text-right" step="0.01" min="0.01"
                                        required>
                                </td>
                                <td>
                                    <input type="text" name="materials[0][material_price]"
                                        class="form-control price-input text-right" readonly>
                                </td>
                                <td>
                                    <input type="text" name="materials[0][subtotal]"
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
                    <a href="{{ route('rfq.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const materialsTable = document.getElementById('materials-table');
            let rowCounter = 0;

            // Function to format number as currency
            function formatCurrency(number) {
                return parseFloat(number).toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Function to handle material selection
            function handleMaterialSelect(select) {
                const row = select.closest('tr');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    const price = selectedOption.dataset.price;
                    const unit = selectedOption.dataset.unit;

                    // Update price input
                    const priceInput = row.querySelector('.price-input');
                    priceInput.value = price;
                    priceInput.dataset.value = price; // Store raw value
                    priceInput.textContent = formatCurrency(price);

                    // Update unit
                    const unitInput = row.querySelector('.unit-input');
                    unitInput.value = unit;

                    // Recalculate subtotal if quantity exists
                    const quantityInput = row.querySelector('.quantity-input');
                    if (quantityInput.value) {
                        calculateSubtotal(row);
                    }
                }
            }

            // Function to calculate subtotal for a row
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').dataset.value) || 0;
                const subtotal = quantity * price;

                const subtotalInput = row.querySelector('.subtotal-input');
                subtotalInput.value = subtotal;
                subtotalInput.textContent = formatCurrency(subtotal);

                calculateTotal();
            }

            // Function to calculate total
            function calculateTotal() {
                let total = 0;
                const subtotalInputs = document.querySelectorAll('.subtotal-input');

                subtotalInputs.forEach(input => {
                    total += parseFloat(input.value) || 0;
                });

                const totalInput = document.getElementById('total-amount');
                totalInput.value = total;
                totalInput.textContent = formatCurrency(total);
            }

            // Function to add new row
            function addNewRow() {
                rowCounter++;
                const tbody = materialsTable.querySelector('tbody');
                const firstSelect = document.querySelector('select[name="materials[0][material_id]"]');
                const newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select name="materials[${rowCounter}][material_id]" class="form-control material-select" required>
                            ${firstSelect.innerHTML}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="materials[${rowCounter}][quantity]" class="form-control quantity-input text-right" 
                            step="0.01" min="0.01" required>
                    </td>
                    <td>
                        <input type="text" name="materials[${rowCounter}][material_price]" class="form-control price-input text-right" readonly>
                    </td>
                    <td>
                        <input type="text" name="materials[${rowCounter}][subtotal]" class="form-control subtotal-input text-right" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row">-</button>
                        <button type="button" class="btn btn-success btn-sm btn-add-row">+</button>
                    </td>
                `;

                tbody.appendChild(newRow);
            }

            // Event Listeners
            materialsTable.addEventListener('change', function(event) {
                if (event.target.classList.contains('material-select')) {
                    handleMaterialSelect(event.target);
                }
            });

            materialsTable.addEventListener('input', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    calculateSubtotal(event.target.closest('tr'));
                }
            });

            materialsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-add-row')) {
                    addNewRow();
                } else if (event.target.classList.contains('btn-remove-row')) {
                    const tbody = materialsTable.querySelector('tbody');
                    if (tbody.children.length > 1) {
                        event.target.closest('tr').remove();
                        calculateTotal();
                    }
                }
            });

            // Initialize existing rows
            document.querySelectorAll('.material-select').forEach(select => {
                if (select.value) {
                    handleMaterialSelect(select);
                }
            });
        });
    </script>
@endsection
