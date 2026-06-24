@extends('layouts.app')

@section('title', 'Detail Stok Masuk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Detail Stok Masuk</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock-ins.print', $stockIn) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print me-2"></i>Cetak
            </a>
            <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Informasi Transaksi</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 130px;">Invoice</th>
                            <td>: <span class="fw-bold">{{ $stockIn->invoice_number }}</span></td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>: {{ $stockIn->supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ $stockIn->date->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>: {{ $stockIn->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>: <span class="fw-bold text-primary">Rp {{ number_format($stockIn->total_price, 0, ',', '.') }}</span></td>
                        </tr>
                        @if($stockIn->notes)
                        <tr>
                            <th>Keterangan</th>
                            <td>: {{ $stockIn->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Daftar Produk</h5>
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
                                @foreach($stockIn->details as $detail)
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
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end text-primary">Rp {{ number_format($stockIn->total_price, 0, ',', '.') }}</th>
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