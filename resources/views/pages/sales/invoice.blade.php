<!DOCTYPE html>
<html>

<head>
    <title>Invoice Sales Order - {{ $sales->sales_code }}</title>
</head>

<body>
    <h1>Invoice Sales Order</h1>
    <p>Kode Sales: {{ $sales->sales_code }}</p>
    <p>Customer: {{ $sales->customer->nama_customer }}</p>
    <p>Tanggal: {{ $sales->created_at->format('d/m/Y') }}</p>

    <table border="1">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total: {{ number_format($sales->total_amount, 2) }}</p>
</body>

</html>
