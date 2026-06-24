<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1a2a4a; }
        .header small { color: #666; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th { background: #1a2a4a; color: white; padding: 10px; text-align: left; }
        table td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .total { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>USAHA<span style="color: #22c55e;">KU</span></h1>
        <small>Laporan Penjualan</small><br>
        <small>Tanggal Cetak: {{ date('d F Y H:i:s') }} WIB</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }} WIB</td>
                <td>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                <td>{{ $sale->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Penjualan: Rp {{ number_format($totalSales, 0, ',', '.') }}<br>
        Total Transaksi: {{ $totalTransactions }}
    </div>

    <div class="footer">
        Dicetak oleh: {{ Auth::user()->name }} | {{ now()->format('d F Y H:i:s') }} WIB
    </div>
</body>
</html>