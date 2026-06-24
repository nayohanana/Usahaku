@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Detail Penjualan</h1>
           <tr>
    <th>Tanggal</th>
    <td>: {{ $sale->date->format('d F Y H:i') }} WIB</td>
</tr>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print me-2"></i>Cetak Nota
            </a>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Transaksi</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 130px;">Invoice</th>
                            <td>: <span class="fw-bold">{{ $sale->invoice_number }}</span></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>: {{ $sale->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: <span class="badge bg-success">{{ ucfirst($sale->status) }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Daftar Produk</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal</th>
                                    <th class="text-end">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</th>
                                </tr>
                                @if($sale->discount > 0)
                                <tr>
                                    <th colspan="4" class="text-end">Diskon</th>
                                    <th class="text-end">Rp {{ number_format($sale->discount, 0, ',', '.') }}</th>
                                </tr>
                                @endif
                                <tr>
                                    <th colspan="4" class="text-end text-primary">Grand Total</th>
                                    <th class="text-end text-primary fw-bold">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Pembayaran</th>
                                    <th class="text-end">Rp {{ number_format($sale->payment, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end text-success">Kembalian</th>
                                    <th class="text-end text-success fw-bold">Rp {{ number_format($sale->change, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection