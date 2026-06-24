<!DOCTYPE html>
<html>
<head>
    <title>Nota - {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: 'Courier New', monospace; padding: 20px; max-width: 400px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header small { color: #666; }
        .info { margin-bottom: 20px; }
        .info p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 8px 4px; text-align: left; border-bottom: 1px dashed #ddd; }
        table th { font-size: 12px; }
        table td { font-size: 13px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total { border-top: 2px solid #000; padding-top: 10px; margin-top: 10px; }
        .total .grand { font-size: 18px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; border-top: 2px dashed #000; padding-top: 20px; color: #666; font-size: 12px; }
        .payment-info { margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 4px; }
        .text-success { color: #22c55e; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>USAHA<span style="color: #22c55e;">KU</span></h1>
        <small>Jl. Sudirman No. 123, Jakarta</small><br>
        <small>Telp: 0812-3456-7890</small>
    </div>

    <div class="info">
        <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
        <p><strong>Tanggal:</strong> {{ $sale->date->format('d F Y H:i:s') }} WIB</p>
        <p><strong>Kasir:</strong> {{ $sale->user->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <div style="display: flex; justify-content: space-between; padding: 4px 0;">
            <span>Subtotal</span>
            <span>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
        </div>
        @if($sale->discount > 0)
        <div style="display: flex; justify-content: space-between; padding: 4px 0;">
            <span>Diskon</span>
            <span>Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 18px; font-weight: bold; border-top: 2px solid #000; padding-top: 8px;">
            <span>TOTAL</span>
            <span>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment-info">
        <div style="display: flex; justify-content: space-between;">
            <span>Pembayaran</span>
            <span>Rp {{ number_format($sale->payment, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; color: #22c55e; font-weight: bold;">
            <span>Kembalian</span>
            <span>Rp {{ number_format($sale->change, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p style="font-size: 10px;">Cetak: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</p>
    </div>
</body>
</html>