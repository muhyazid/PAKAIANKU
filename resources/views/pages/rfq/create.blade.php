@extends('layouts.master')

@section('title', 'Tambah RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Tambah Request for Quotation (RFQ)</h3>
    </div>

    {{-- Main Form Card --}}
    <div class="card">
        <div class="card-body">
            {{-- Form with validation errors handling --}}
            <form action="{{ route('rfq.store') }}" method="POST">
                @csrf

                {{-- RFQ Code Field - Auto-generated and readonly --}}
                <div class="form-group">
                    <label for="rfq_code" class="text-light">Kode RFQ</label>
                    <input type="text" class="form-control @error('rfq_code') is-invalid @enderror" name="rfq_code"
                        value="{{ $rfqCode }}" readonly>
                    @error('rfq_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Supplier Selection --}}
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

                {{-- Quotation Date --}}
                <div class="form-group">
                    <label for="quotation_date" class="text-light">Tanggal Penawaran</label>
                    <input type="date" class="form-control @error('quotation_date') is-invalid @enderror"
                        name="quotation_date" value="{{ old('quotation_date') }}" required>
                    @error('quotation_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Materials Section --}}
                <h4 class="text-light mt-4">Material yang Diminta</h4>
                <div class="table-responsive">
                    <table class="table table-dark" id="materials-table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="materials[0][material_id]" class="form-control" required>
                                        <option value="">Pilih Material</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="materials[0][quantity]" class="form-control" step="0.01"
                                        required>
                                </td>
                                <td>
                                    <select name="materials[0][unit]" class="form-control" required>
                                        <option value="gram">gram</option>
                                        <option value="meter">meter</option>
                                        <option value="pcs">pcs</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-add-row">+</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Submit Button --}}
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('rfq.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for Dynamic Form Fields --}}
    @push('scripts')
        <script>
            // Initialize row counter
            let rowCount = 1;

            // Add new material row
            document.querySelector('.btn-add-row').addEventListener('click', function() {
                let table = document.querySelector('#materials-table tbody');
                let newRow = document.createElement('tr');

                // Generate new row HTML with incremented index
                newRow.innerHTML = `
                <td>
                    <select name="materials[${rowCount}][material_id]" class="form-control" required>
                        <option value="">Pilih Material</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="materials[${rowCount}][quantity]" class="form-control" 
                           step="0.01" required>
                </td>
                <td>
                    <select name="materials[${rowCount}][unit]" class="form-control" required>
                        <option value="gram">gram</option>
                        <option value="meter">meter</option>
                        <option value="pcs">pcs</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-remove-row">-</button>
                </td>
            `;

                // Add the new row to the table
                table.appendChild(newRow);
                rowCount++;
                // Add event listener to remove button
                newRow.querySelector('.btn-remove-row').addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });
        </script>
    @endpush
@endsection
