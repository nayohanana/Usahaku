@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Laporan Laba Rugi</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export.profit-pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('reports.export.profit-excel', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Excel
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Penjualan</h6>
                    <h3 class="fw-bold text-primary">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                    <h3 class="fw-bold text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm {{ $profit >= 0 ? 'border-success' : 'border-danger' }}">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Laba / Rugi</h6>
                    <h3 class="fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $profit >= 0 ? '+' : '-' }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
                    </h3>
                    <small class="text-muted">Margin: {{ number_format($profitMargin, 1) }}%</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th style="width: 200px;">Pendapatan</th>
                    <td>Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Pengeluaran</th>
                    <td class="text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-top">
                    <th class="fw-bold fs-5">Laba Bersih</th>
                    <td class="fw-bold fs-5 {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $profit >= 0 ? '+' : '-' }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Margin Keuntungan</th>
                    <td>{{ number_format($profitMargin, 1) }}%</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection