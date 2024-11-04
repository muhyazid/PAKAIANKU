@extends('layouts.master')

@section('content')
    <div class="container">
        <h3>Tambah RFQ</h3>
        <form action="{{ route('rfq.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Pilih Vendor</label>
                <select name="supplier_id" class="form-control" id="supplier" required>
                    <option value="">Pilih Supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kode RFQ</label>
                <input type="text" name="kode_rfq" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tanggal Penawaran</label>
                <input type="datetime-local" name="tanggal_penawaran" class="form-control" required>
            </div>

            <h5>Daftar Material</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Spesifikasi</th>
                        <th>Satuan</th>
                        <th>Kuantitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="material-list">
                    <!-- Material rows will be appended here by JavaScript -->
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script>
        document.getElementById('supplier').addEventListener('change', function() {
            const supplierId = this.value;
            const materialList = document.getElementById('material-list');

            // Clear the material list
            materialList.innerHTML = '';

            if (supplierId) {
                // Fetch materials by supplier
                fetch(`/supplier/${supplierId}/materials`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the material list with the fetched data
                        data.forEach((material, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${material.nama_bahan}</td>
                                <td><input type="text" name="materials[${index}][spesifikasi]" class="form-control" required></td>
                                <td><input type="text" name="materials[${index}][satuan]" class="form-control" value="${material.satuan}" required></td>
                                <td><input type="number" name="materials[${index}][kuantitas]" class="form-control" required></td>
                                <td><button type="button" class="btn btn-danger remove-material">Hapus</button></td>
                            `;
                            materialList.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error fetching materials:', error));
            }
        });

        // Function to remove a material row
        document.getElementById('material-list').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-material')) {
                event.target.closest('tr').remove();
            }
        });
    </script>
@endsection
