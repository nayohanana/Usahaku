@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<style>
    .summary-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
    }
    .summary-card .icon-bg {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .summary-card .icon-bg.blue { background: rgba(26, 42, 74, 0.1); color: #1a2a4a; }
    .summary-card .icon-bg.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .summary-card .icon-bg.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .summary-card .icon-bg.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Laporan</h1>
            <p class="text-muted">Ringkasan kinerja bisnis Anda</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('reports.sales') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line me-2"></i>Penjualan
            </a>
            <a href="{{ route('reports.expenses') }}" class="btn btn-outline-danger">
                <i class="fas fa-chart-pie me-2"></i>Pengeluaran
            </a>
            <a href="{{ route('reports.profit') }}" class="btn btn-outline-success">
                <i class="fas fa-coins me-2"></i>Laba Rugi
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Penjualan</span>
                            <h4 class="fw-bold text-primary mt-1 mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                        </div>
                        <div class="icon-bg blue">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Pengeluaran</span>
                            <h4 class="fw-bold text-danger mt-1 mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                        </div>
                        <div class="icon-bg red">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Laba Bersih</span>
                            <h4 class="fw-bold text-success mt-1 mb-0">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h4>
                        </div>
                        <div class="icon-bg green">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card summary-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Transaksi</span>
                            <h4 class="fw-bold mt-1 mb-0">{{ number_format($totalTransactions) }}</h4>
                        </div>
                        <div class="icon-bg orange">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold">
                <i class="fas fa-chart-area me-2 text-primary"></i>Performa 7 Hari Terakhir
            </h6>
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                    <span class="d-inline-block rounded-circle bg-primary me-1" style="width: 8px; height: 8px;"></span>
                    Penjualan
                </span>
                <span class="badge bg-danger bg-opacity-10 text-danger">
                    <span class="d-inline-block rounded-circle bg-danger me-1" style="width: 8px; height: 8px;"></span>
                    Pengeluaran
                </span>
            </div>
        </div>
        <div class="card-body">
            <canvas id="weeklyChart" height="250"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($weeklySales);
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    
    const gradSales = ctx.createLinearGradient(0, 0, 0, 300);
    gradSales.addColorStop(0, 'rgba(26, 42, 74, 0.4)');
    gradSales.addColorStop(1, 'rgba(26, 42, 74, 0)');
    
    const gradExpenses = ctx.createLinearGradient(0, 0, 0, 300);
    gradExpenses.addColorStop(0, 'rgba(239, 68, 68, 0.4)');
    gradExpenses.addColorStop(1, 'rgba(239, 68, 68, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [
                {
                    label: 'Penjualan',
                    data: data.map(d => d.sales),
                    borderColor: '#1a2a4a',
                    backgroundColor: gradSales,
                    borderWidth: 3,
                    pointBackgroundColor: '#1a2a4a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Pengeluaran',
                    data: data.map(d => d.expenses),
                    borderColor: '#ef4444',
                    backgroundColor: gradExpenses,
                    borderWidth: 3,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#1e293b',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + 
                                   new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection