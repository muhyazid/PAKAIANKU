@extends('layouts.master')

@section('title', 'Edit BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Edit Bill of Materials (BoM)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('boms.update', $bom->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Dropdown untuk memilih produk -->
                <div class="form-group">
                    <label for="product_id" style="color: white;">Nama Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $bom->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kode BoM -->
                <div class="form-group">
                    <label for="production_code" style="color: white;">Kode BoM</label>
                    <input type="text" class="form-control subtotal-input @error('production_code') is-invalid @enderror"
                        name="production_code" id="production_code" value="{{ $bom->production_code }}" readonly>
                    @error('production_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah Produksi -->
                <div class="form-group">
                    <label for="quantity" style="color: white;">Jumlah Produksi</label>
                    <input type="number" class="form-control subtotal-input @error('quantity') is-invalid @enderror"
                        name="quantity" id="quantity" value="{{ $bom->quantity }}" readonly>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Komponen / Material -->
                <h4 class="text-light mt-4">Komponen / Material</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-bordered" id="components-table">
                        <thead>
                            <tr>
                                <th class="text-center">Material</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bom->materials as $index => $material)
                                <tr>
                                    <td>
                                        <select name="materials[{{ $index }}][material_id]"
                                            class="form-control item-select" required>
                                            <option value="">Pilih Material</option>
                                            @foreach ($materials as $availableMaterial)
                                                <option value="{{ $availableMaterial->id }}"
                                                    {{ $availableMaterial->id == $material->id ? 'selected' : '' }}>
                                                    {{ $availableMaterial->nama_bahan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="materials[{{ $index }}][quantity]"
                                            class="form-control quantity-input text-right"
                                            value="{{ $material->pivot->quantity }}" step="0.01" min="0.01"
                                            required>
                                    </td>
                                    <td>
                                        <select name="materials[{{ $index }}][unit]" class="form-control">
                                            <option value="gram"
                                                {{ $material->pivot->unit == 'gram' ? 'selected' : '' }}>
                                                gram
                                            </option>
                                            <option value="meter"
                                                {{ $material->pivot->unit == 'meter' ? 'selected' : '' }}>
                                                meter
                                            </option>
                                            <option value="pcs" {{ $material->pivot->unit == 'pcs' ? 'selected' : '' }}>
                                                pcs
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-row">-</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('boms.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const componentsTable = document.getElementById('components-table');
            let rowCount = {{ $bom->materials->count() }};

            function addNewRow() {
                const newRow = componentsTable.querySelector('tbody tr').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                const newIndex = rowCount;

                newRow.querySelectorAll('[name^="materials["]').forEach(element => {
                    element.name = element.name.replace(/\[\d+\]/, `[${newIndex}]`);
                });

                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.className = 'btn btn-danger btn-sm btn-remove-row';
                deleteButton.textContent = '-';
                deleteButton.onclick = function() {
                    newRow.remove();
                };

                const actionCell = newRow.querySelector('td:last-child');
                actionCell.innerHTML = '';
                actionCell.appendChild(deleteButton);

                componentsTable.querySelector('tbody').appendChild(newRow);
                rowCount++;
            }

            componentsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-add-row')) {
                    addNewRow();
                } else if (event.target.classList.contains('btn-remove-row')) {
                    event.target.closest('tr').remove();
                }
            });
        });
    </script>
@endsection
