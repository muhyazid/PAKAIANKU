@extends('layouts.master')

@section('title', 'Edit Manufacturing Order')

@section('content')
    <h3>Edit Manufacturing Order</h3>

    <form action="{{ route('manufacturing_orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">Produk</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $order->product_id ? 'selected' : '' }}>
                        {{ $product->nama_produk }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kode_MO">Kode MO</label>
            <input type="text" name="kode_MO" class="form-control" value="{{ $order->kode_MO }}" required>
        </div>

        <div class="form-group">
            <label for="quantity">Kuantitas</label>
            <input type="number" name="quantity" class="form-control" value="{{ $order->quantity }}" required>
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" class="form-control"
                value="{{ date('Y-m-d\TH:i', strtotime($order->start_date)) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="Draft" {{ $order->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Confirmed" {{ $order->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="Done" {{ $order->status == 'Done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('manufacturing_orders.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
