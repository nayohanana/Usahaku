<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengeluaran</title>
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
        <small>Laporan Pengeluaran</small><br>
        <small>Tanggal Cetak: {{ date('d F Y H:i:s') }} WIB</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $index => $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                <td>{{ $expense->category }}</td>
                <td>{{ $expense->description }}</td>
                <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                <td>{{ $expense->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pengeluaran: Rp {{ number_format($totalExpenses, 0, ',', '.') }}
    </div>

    <div class="footer">
        Dicetak oleh: {{ Auth::user()->name }} | {{ now()->format('d F Y H:i:s') }} WIB
    </div>
</body>
</html>