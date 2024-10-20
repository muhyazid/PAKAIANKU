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

                <!-- Kode Produksi -->
                <div class="form-group">
                    <label for="production_code" style="color: white;">Kode BoM</label>
                    <input type="text" class="form-control" name="production_code" value="{{ $bom->production_code }}"
                        required>
                </div>

                <!-- Jumlah Produksi (hanya ditampilkan) -->
                <div class="form-group">
                    <label for="quantity" style="color: white;">Jumlah Produksi</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $bom->quantity }}" disabled>
                </div>

                <!-- Komponen / Material -->
                <h4 style="color: white;">Komponen / Material</h4>
                <table class="table table-bordered" id="components-table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bom->materials as $index => $material)
                            <tr>
                                <td>
                                    <select name="materials[{{ $index }}][material_id]" class="form-control"
                                        required>
                                        <option value="">Pilih Material</option>
                                        @foreach ($materials as $availableMaterial)
                                            <option value="{{ $availableMaterial->id }}"
                                                {{ $availableMaterial->id == $material->id ? 'selected' : '' }}>
                                                {{ $availableMaterial->nama_bahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="materials[{{ $index }}][quantity]"
                                        class="form-control" value="{{ $material->pivot->quantity }}" required></td>
                                <td>
                                    <select name="materials[{{ $index }}][unit]" class="form-control">
                                        <option value="gram" {{ $material->pivot->unit == 'gram' ? 'selected' : '' }}>
                                            gram</option>
                                        <option value="meter" {{ $material->pivot->unit == 'meter' ? 'selected' : '' }}>
                                            meter</option>
                                        <option value="pcs" {{ $material->pivot->unit == 'pcs' ? 'selected' : '' }}>pcs
                                        </option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-danger btn-remove-row">-</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        let rowCount = {{ $bom->materials->count() }};

        document.querySelector('.btn-add-row').addEventListener('click', function() {
            let table = document.querySelector('#components-table tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="materials[${rowCount}][material_id]" class="form-control" required>
                        <option value="">Pilih Material</option>
                        @foreach ($materials as $availableMaterial)
                            <option value="{{ $availableMaterial->id }}">{{ $availableMaterial->nama_bahan }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="materials[${rowCount}][quantity]" class="form-control" required></td>
                <td>
                    <select name="materials[${rowCount}][unit]" class="form-control">
                        <option value="gram">gram</option>
                        <option value="meter">meter</option>
                        <option value="pcs">pcs</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-remove-row">-</button></td>
            `;
            table.appendChild(newRow);
            rowCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
