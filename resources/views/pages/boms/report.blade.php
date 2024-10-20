<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoM Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        .divider {
            border-bottom: 2px solid #000;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            padding: 10px;
        }

        td {
            padding: 8px;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>BoM Report - {{ $bom->product->nama_produk }}</h1>
    <p><strong>Kode Produksi:</strong> {{ $bom->production_code }}</p>
    <p><strong>Jumlah Produksi:</strong> {{ $bom->quantity }}</p>

    <div class="divider"></div>

    <h3>Detail Bahan Baku dan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>Bahan / Material</th>
                <th>Quantity</th>
                <th>Harga Satuan (Rp)</th>
                <th>Product Cost (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bom->materials as $material)
                <tr>
                    <td>{{ $material->nama_bahan }}</td>
                    <td>{{ $material->pivot->quantity }}</td>
                    <td>{{ number_format($material->price, 2, ',', '.') }}</td>
                    <td>{{ number_format($material->price * $material->pivot->quantity, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <h3>Total BoM Cost: Rp {{ number_format($totalBoMCost, 2, ',', '.') }}</h3>
    </div>
</body>

</html>
