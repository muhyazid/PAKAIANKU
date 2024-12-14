@extends('layouts.master')

@section('title', 'Edit Manufacturing Order')

@section('content')
    <div class="page-header">
        <h3 class="page-title text-light">Edit Manufacturing Order</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manufacturing_orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_MO" class="text-light">Kode MO</label>
                            <input type="text" name="kode_MO"
                                class="form-control subtotal-input @error('kode_MO') is-invalid @enderror"
                                value="{{ $order->kode_MO }}" readonly required>
                            @error('kode_MO')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_id" class="text-light">Produk</label>
                            <select name="product_id" id="product_id"
                                class="form-control @error('product_id') is-invalid @enderror" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $order->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity" class="text-light">Kuantitas</label>
                            <input type="number" name="quantity" id="mo_quantity"
                                class="form-control @error('quantity') is-invalid @enderror" value="{{ $order->quantity }}"
                                required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date" class="text-light">Tanggal Mulai</label>
                            <input type="datetime-local" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ \Carbon\Carbon::parse($order->start_date)->format('Y-m-d\TH:i') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <!-- Materials Section -->
                <div class="form-group mt-4">
                    <h4 class="text-light">Materials Required</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Unit</th>
                                    <th>Required Quantity</th>
                                    <th>To Consume</th>
                                </tr>
                            </thead>
                            <tbody id="materials-container">
                                @foreach ($order->materials as $material)
                                    <tr>
                                        <td>{{ $material->nama_bahan }}</td>
                                        <td>{{ $material->pivot->unit ?? '-' }}</td>
                                        <td>{{ $material->pivot->quantity }}</td>
                                        <td>
                                            <input type="hidden" name="materials[{{ $material->id }}][material_id]"
                                                value="{{ $material->id }}">
                                            <input type="number" name="materials[{{ $material->id }}][to_consume]"
                                                value="{{ $material->pivot->to_consume }}" class="form-control"
                                                step="0.01" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('manufacturing_orders.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('mo_quantity');
            const materialsContainer = document.getElementById('materials-container');

            function updateMaterials() {
                const productId = productSelect.value;
                const quantity = parseFloat(quantityInput.value) || 0;

                if (!productId) {
                    return;
                }

                fetch(`/manufacturing_orders/materials/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Preserve existing materials if possible
                        const updatedMaterials = data.materials.map(material => {
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

                        materialsContainer.innerHTML = updatedMaterials;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            productSelect.addEventListener('change', updateMaterials);
            quantityInput.addEventListener('input', updateMaterials);
        });
    </script>
@endsection
