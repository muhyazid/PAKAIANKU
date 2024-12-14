@extends('layouts.master')

@section('title', 'Tambah BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Tambah Bill of Materials (BoM)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('boms.store') }}" method="POST">
                @csrf

                <!-- Dropdown untuk memilih produk -->
                <div class="form-group">
                    <label for="product_id" style="color: white;">Nama Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kode Produksi -->
                <div class="form-group">
                    <label for="production_code" style="color: white;">Kode BoM</label>
                    <input type="text" class="form-control subtotal-input @error('production_code') is-invalid @enderror"
                        name="production_code" id="production_code" value="{{ $nextCode }}" readonly>
                    @error('production_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Jumlah Produksi -->
                <div class="form-group">
                    <label for="quantity" style="color: white;">Jumlah Produksi</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                        required>
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
                            <tr>
                                <td>
                                    <select name="materials[0][material_id]" class="form-control item-select" required>
                                        <option value="">Pilih Material</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="materials[0][quantity]"
                                        class="form-control quantity-input text-right" step="0.01" min="0.01"
                                        required>
                                </td>
                                <td>
                                    <select name="materials[0][unit]" class="form-control">
                                        <option value="gram">gram</option>
                                        <option value="meter">meter</option>
                                        <option value="pcs">pcs</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-add-row">+</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('boms.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const componentsTable = document.getElementById('components-table');

            function addNewRow() {
                const newRow = componentsTable.querySelector('tbody tr').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                const newIndex = componentsTable.querySelectorAll('tbody tr').length;

                newRow.querySelectorAll('[name^="materials[0]"]').forEach(element => {
                    element.name = element.name.replace('[0]', `[${newIndex}]`);
                });

                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.className = 'btn btn-danger btn-sm btn-delete-row';
                deleteButton.textContent = '-';
                deleteButton.onclick = function() {
                    newRow.remove();
                };

                const actionCell = newRow.querySelector('td:last-child');
                actionCell.innerHTML = '';
                actionCell.appendChild(deleteButton);

                componentsTable.querySelector('tbody').appendChild(newRow);
            }

            componentsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-add-row')) {
                    addNewRow();
                } else if (event.target.classList.contains('btn-delete-row')) {
                    event.target.closest('tr').remove();
                }
            });
        });
    </script>
@endsection
