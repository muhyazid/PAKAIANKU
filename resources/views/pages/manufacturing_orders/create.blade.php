@extends('layouts.master')

@section('title', 'Tambah Manufacturing Order')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Tambah Manufacturing Order</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manufacturing-orders.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="product_id">Product</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="datetime-local" name="start_date" class="form-control" required>
                </div>

                <h4>Materials</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>To Consume</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            <tr>
                                <td>
                                    <input type="checkbox" name="materials[{{ $loop->index }}][material_id]"
                                        value="{{ $material->id }}">
                                    {{ $material->nama_bahan }}
                                </td>
                                <td>
                                    <input type="number" name="materials[{{ $loop->index }}][to_consume]"
                                        class="form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="materials[{{ $loop->index }}][unit]" class="form-control"
                                        required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
