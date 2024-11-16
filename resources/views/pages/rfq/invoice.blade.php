@extends('layouts.pdf')

@section('title', 'Invoice RFQ')

@section('content')
    <div class="invoice">
        <div class="text-center" style="margin-bottom: 20px;">
            <h1 style="color: #4a4a4a; font-weight: bold; margin-bottom: 5px;">PT ABC</h1>
            <p style="margin: 0;">Rusunawa ITN Malang Kampus 2</p>
            <p style="margin: 0;">0812 3385 8380</p>
        </div>
        <hr style="border: 1px solid #ddd; margin-bottom: 20px;">

        <div class="text-center" style="margin-bottom: 20px;">
            <h2 style="color: #007bff; font-weight: bold;">Invoice</h2>
            <p style="color: #d9534f;">Submitted on {{ now()->format('d/m/Y') }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <table style="width: 100%; font-size: 12px;">
                <tr>
                    <td style="font-weight: bold; width: 20%;">Invoice for:</td>
                    <td style="width: 30%;">{{ $rfq->supplier->nama }}</td>
                    <td style="font-weight: bold; width: 20%;">Invoice #:</td>
                    <td style="width: 30%;">{{ $rfq->rfq_code }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Payable to:</td>
                    <td>PT ABC</td>
                    <td style="font-weight: bold;">Due date:</td>
                    <td>{{ $rfq->quotation_date->addDays(30)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Payment Method:</td>
                    <td colspan="3">{{ ucfirst($rfq->payment_method) }}</td> <!-- Menampilkan metode pembayaran -->
                </tr>
            </table>
        </div>

        <div style="margin-bottom: 30px;">
            <h5 style="font-weight: bold; margin-bottom: 10px;">Detail Material</h5>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f2f2f2; text-align: left;">
                        <th style="padding: 8px; border: 1px solid #ddd;">Description</th>
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: center;">Qty</th>
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Unit price</th>
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Total price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rfq->items as $item)
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;">{{ $item->material->nama_bahan }}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">{{ $item->quantity }}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">
                                Rp {{ number_format($item->material_price, 2, ',', '.') }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">
                                Rp {{ number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"
                            style="padding: 8px; border: 1px solid #ddd; text-align: right; font-weight: bold;">
                            Subtotal
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">
                            Rp {{ number_format($rfq->items->sum('subtotal'), 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="padding: 8px; border: 1px solid #ddd; text-align: right; font-weight: bold;">
                            PPN 11%
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">
                            Rp {{ number_format($rfq->items->sum('subtotal') * 0.11, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="padding: 8px; border: 1px solid #ddd; text-align: right; font-weight: bold;">
                            Total
                        </td>
                        <td
                            style="padding: 8px; border: 1px solid #ddd; text-align: right; font-weight: bold; color: #d9534f;">
                            Rp {{ number_format($rfq->items->sum('subtotal') * 1.11, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top: 30px; font-size: 10px; color: #888;">
            <p>Notes: Terima kasih atas kerja sama Anda.</p>
        </div>
    </div>
@endsection
