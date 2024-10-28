@extends('layouts.master')

@section('title', 'Tambah Manufacturing Order')

@section('content')
    <h3>Tambah Manufacturing Order</h3>
    <form action="{{ route('manufacturing_orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Produk</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kode_MO">Kode MO</label>
            <input type="text" name="kode_MO" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="quantity">Kuantitas</label>
            <input type="number" name="quantity" id="mo_quantity" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" class="form-control" required>
        </div>

        {{-- <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="Draft">Draft</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Done">Done</option>
            </select>
        </div> --}}

        <!-- Materials Section -->
        <div class="form-group mt-4">
            <h4>Materials Required</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Unit</th>
                            <th>Required Quantity</th>
                            <th>To Consume</th>
                        </tr>
                    </thead>
                    <tbody id="materials-container">
                        <!-- Materials will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('mo_quantity');
            const materialsContainer = document.getElementById('materials-container');

            function updateMaterials() {
                const productId = productSelect.value;
                const quantity = parseFloat(quantityInput.value) || 0;

                if (!productId) {
                    materialsContainer.innerHTML = '';
                    return;
                }

                fetch(`/manufacturing_orders/materials/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            materialsContainer.innerHTML =
                                `<tr><td colspan="4" class="text-center">${data.error}</td></tr>`;
                            return;
                        }

                        materialsContainer.innerHTML = data.materials.map(material => {
                            const requiredQty = (material.pivot.quantity * quantity).toFixed(2);
                            return `
                                <tr>
                                    <td>${material.nama_bahan}</td>
                                    <td>${material.pivot.unit}</td>
                                    <td>${requiredQty}</td>
                                    <td>
                                        <input type="hidden" name="materials[${material.id}][material_id]" value="${material.id}">
                                        <input type="number" 
                                               name="materials[${material.id}][to_consume]" 
                                               value="${requiredQty}" 
                                               class="form-control"
                                               step="0.01"
                                               required>
                                    </td>
                                </tr>
                            `;
                        }).join('');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        materialsContainer.innerHTML =
                            '<tr><td colspan="4" class="text-center">Error loading materials</td></tr>';
                    });
            }

            productSelect.addEventListener('change', updateMaterials);
            quantityInput.addEventListener('input', updateMaterials);
        });
    </script>
@endsection



{{-- @extends('layouts.master')

@section('title', 'Tambah Manufacturing Order')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Permintaan Pesanan</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manufacturing_orders.store') }}" method="POST">
                @csrf

                <!-- Pilih Produk -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product_id">Nama Produk</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="bom_id">Kode BoM</label>
                        <select name="bom_id" id="bom_id" class="form-control" disabled>
                            <option value="">Pilih BoM</option>
                            <!-- BoM akan otomatis terisi melalui JS -->
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="quantity">Kuantitas Produksi</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="start_date">Tanggal Mulai Produksi</label>
                        <input type="datetime-local" name="start_date" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="end_date">Tanggal Selesai Produksi</label>
                        <input type="datetime-local" name="end_date" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Draft">Draft</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>


                </div>

                <!-- Tabel Material yang digunakan -->
                <h4>Komponen / Material</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>To Consume</th>
                            <th>Quantity</th>
                            <th>Consumed</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="material-list">
                        <!-- Material dari BoM akan di-append disini oleh JavaScript -->
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk mengisi Material secara otomatis -->
    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            let productId = this.value;
            let bomSelect = document.getElementById('bom_id');
            let materialList = document.getElementById('material-list');
            let quantity = document.getElementById('quantity').value || 1;

            if (!productId) {
                bomSelect.disabled = true;
                materialList.innerHTML = '';
                return;
            }

            // Mengambil data BoM menggunakan Ajax
            fetch(`/boms/materials/${productId}`)
                .then(response => response.json())
                .then(data => {
                    bomSelect.innerHTML = '<option value="">Pilih BoM</option>';
                    data.boms.forEach(bom => {
                        bomSelect.innerHTML +=
                            `<option value="${bom.id}">${bom.production_code}</option>`;
                    });
                    bomSelect.disabled = false;

                    // Menampilkan Material di tabel
                    materialList.innerHTML = '';
                    data.materials.forEach(material => {
                        let toConsume = material.pivot.quantity * quantity;
                        materialList.innerHTML += `
                        <tr>
                            <td>${material.nama_bahan}</td>
                            <td><input type="number" name="materials[${material.id}][to_consume]" value="${toConsume}" class="form-control" readonly></td>
                            <td><input type="number" name="materials[${material.id}][quantity]" value="${toConsume}" class="form-control" readonly></td>
                            <td><input type="checkbox" name="materials[${material.id}][consumed]" value="1"></td>
                            <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                        </tr>`;
                    });
                })
                .catch(error => console.error('Error fetching BoM:', error));
        });

        // Recalculate when quantity is changed
        document.getElementById('quantity').addEventListener('input', function() {
            let productId = document.getElementById('product_id').value;
            if (!productId) return;

            let quantity = this.value || 1;
            let materialList = document.getElementById('material-list');

            // Fetch BoM again with updated quantity
            fetch(`/boms/materials/${productId}`)
                .then(response => response.json())
                .then(data => {
                    materialList.innerHTML = '';
                    data.materials.forEach(material => {
                        let toConsume = material.pivot.quantity * quantity;
                        materialList.innerHTML += `
                        <tr>
                            <td>${material.nama_bahan}</td>
                            <td><input type="number" name="materials[${material.id}][to_consume]" value="${toConsume}" class="form-control" readonly></td>
                            <td><input type="number" name="materials[${material.id}][quantity]" value="${toConsume}" class="form-control" readonly></td>
                            <td><input type="checkbox" name="materials[${material.id}][consumed]" value="1"></td>
                            <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                        </tr>`;
                    });
                })
                .catch(error => console.error('Error fetching BoM:', error));
        });
    </script>
@endsection --}}
