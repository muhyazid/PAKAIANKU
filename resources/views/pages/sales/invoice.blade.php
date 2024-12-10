<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20mm 15mm 20mm 15mm;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
        }

        .header h1 {
            font-size: 28px;
            text-align: right;
            margin: 0;
        }

        .company-info,
        .bill-to {
            margin-bottom: 20px;
        }

        .company-info p,
        .bill-to p {
            margin: 5px 0;
            font-size: 14px;
        }

        .bill-to {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .bill-to-left,
        .bill-to-right {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
        }

        .totals-row td {
            text-align: right;
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Company Logo">
            <h1>INVOICE</h1>
        </div>

        <div class="company-info">
            <p><strong>Best Cleaning Company</strong></p>
            <p>14 Guild Street</p>
            <p>EC4P 0NQ</p>
            <p>United Kingdom</p>
            <p>Email: email@yourbusinessname.co.uk</p>
        </div>

        <div class="bill-to">
            <div class="bill-to-left">
                <h3>BILL TO:</h3>
                <p>{{ $sale->customer->nama_customer }}</p>
                <p>{{ $sale->billing_address }}</p>
            </div>
            <div class="bill-to-right">
                <p><strong>Invoice No:</strong> {{ $sale->sales_code }}</p>
                <p><strong>Issue Date:</strong> {{ $sale->created_at->format('d/m/Y') }}</p>
                <p><strong>Due Date:</strong> {{ $sale->expiry_date }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->nama_produk }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="totals-row">
                    <td colspan="3">Total (USD)</td>
                    <td>{{ number_format($sale->items->sum('subtotal'), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="signature">
            <p>Issued by,</p>
            <p><strong>Best Cleaning Company</strong></p>
        </div>
    </div>
</body>

</html>
