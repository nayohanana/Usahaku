@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Laporan Penjualan</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('reports.sales') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small fw-semibold text-uppercase">Total Penjualan</span>
                    <h4 class="fw-bold text-primary mt-1 mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small fw-semibold text-uppercase">Total Transaksi</span>
                    <h4 class="fw-bold mt-1 mb-0">{{ number_format($totalTransactions) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('reports.export.sales-pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('reports.export.sales-excel', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Excel
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Invoice</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-end">Total</th>
                            <th class="py-3">Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="py-3 fw-semibold">{{ $sale->invoice_number }}</td>
                            <td class="py-3">{{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                            <td class="py-3 text-end fw-bold">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                            <td class="py-3">{{ $sale->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3 d-block opacity-25"></i>
                                <h5 class="text-muted">Belum ada transaksi</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection