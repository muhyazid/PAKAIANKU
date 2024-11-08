@extends('layouts.master')

@section('title', 'Edit RFQ')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Edit Request for Quotation (RFQ)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('rfq.update', $rfq->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Similar form fields as create.blade.php but with existing values --}}
                <div class="form-group">
                    <label for="rfq_code" class="text-light">Kode RFQ</label>
                    <input type="text" class="form-control" name="rfq_code" value="{{ $rfq->rfq_code }}" readonly>
                </div>

                <div class="form-group">
                    <label for="supplier_id" class="text-light">Supplier</label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $rfq->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quotation_date" class="text-light">Tanggal Penawaran</label>
                    <input type="date" class="form-control" name="quotation_date"
                        value="{{ $rfq->quotation_date->format('Y-m-d') }}" required>
                </div>

                <h4 class="text-light mt-4">Material yang Diminta</h4>
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
                        @foreach ($rfq->items as $index => $item)
                            <tr>
                                <td>
                                    <select name="materials[{{ $index }}][material_id]" class="form-control"
                                        required>
                                        <option value="">Pilih Material</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}"
                                                {{ $item->material_id == $material->id ? 'selected' : '' }}>
                                                {{ $material->nama_bahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="materials[{{ $index }}][quantity]"
                                        value="{{ $item->quantity }}" class="form-control" step="0.01" required>
                                </td>
                                <td>
                                    <select name="materials[{{ $index }}][unit]" class="form-control" required>
                                        @foreach (['gram', 'meter', 'pcs'] as $unit)
                                            <option value="{{ $unit }}"
                                                {{ $item->unit == $unit ? 'selected' : '' }}>
                                                {{ $unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if ($index == 0)
                                        <button type="button" class="btn btn-success btn-add-row">+</button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-remove-row">-</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('rfq.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Similar JavaScript as create.blade.php for dynamic rows
            let rowCount = {{ count($rfq->items) }};

            // Add new material row functionality
            document.querySelector('.btn-add-row').addEventListener('click', function() {
                // Similar implementation as in create.blade.php
                // ...
            });

            // Remove row functionality for existing remove buttons
            document.querySelectorAll('.btn-remove-row').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });
        </script>
    @endpush
@endsection
