<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1a2a4a; }
        .header small { color: #666; }
        .summary { width: 100%; margin: 20px 0; }
        .summary td { padding: 12px; border-bottom: 1px solid #ddd; }
        .summary .label { font-weight: bold; }
        .total-row { font-size: 18px; font-weight: bold; }
        .profit-positive { color: #22c55e; }
        .profit-negative { color: #ef4444; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>USAHA<span style="color: #22c55e;">KU</span></h1>
        <small>Laporan Laba Rugi</small><br>
        <small>Tanggal Cetak: {{ date('d F Y H:i:s') }} WIB</small>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Pendapatan</td>
            <td>Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Pengeluaran</td>
            <td>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td class="label">Laba Bersih</td>
            <td class="{{ $profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                {{ $profit >= 0 ? '+' : '-' }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="label">Margin Keuntungan</td>
            <td>{{ number_format($profitMargin, 1) }}%</td>
        </tr>
    </table>

    <div class="footer">
        Dicetak oleh: {{ Auth::user()->name }} | {{ now()->format('d F Y H:i:s') }} WIB
    </div>
</body>
</html>