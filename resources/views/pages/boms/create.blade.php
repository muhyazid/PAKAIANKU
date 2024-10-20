@extends('layouts.master')

@section('title', 'Tambah BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Tambah Bill of Materials (BoM)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('boms.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="product_id" class="text-light">Nama Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="production_code" class="text-light">Kode BoM</label>
                    <input type="text" class="form-control" name="production_code" required>
                </div>

                <div class="form-group">
                    <label for="quantity" class="text-light">Jumlah Produksi</label>
                    <input type="number" class="form-control" name="quantity" required>
                </div>

                <h4 class="text-light">Komponen / Material</h4>
                <table class="table table-bordered table-dark" id="components-table">
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
                            <td><input type="number" name="materials[0][quantity]" class="form-control" required></td>
                            <td>
                                <select name="materials[0][unit]" class="form-control">
                                    <option value="gram">gram</option>
                                    <option value="meter">meter</option>
                                    <option value="pcs">pcs</option>
                                </select>
                            </td>
                            <td><button type="button" class="btn btn-success btn-add-row">+</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        let rowCount = 1;

        document.querySelector('.btn-add-row').addEventListener('click', function() {
            let table = document.querySelector('#components-table tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="materials[${rowCount}][material_id]" class="form-control" required>
                        <option value="">Pilih Material</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
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

    <style>
        .text-light {
            color: #f8f9fa !important;
            /* Warna teks terang */
        }

        label {
            color: #f8f9fa !important;
            /* Warna label form menjadi terang */
        }

        h3,
        h4 {
            color: #f8f9fa !important;
            /* Warna judul menjadi terang */
        }
    </style>
@endsection
