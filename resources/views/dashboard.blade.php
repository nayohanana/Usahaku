@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }
    .stat-card .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-card .icon-wrapper.blue { background: rgba(26, 42, 74, 0.1); color: #1a2a4a; }
    .stat-card .icon-wrapper.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-card .icon-wrapper.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-card .icon-wrapper.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.5px;
    }
    
    .trend-badge {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 50px;
        font-weight: 600;
    }
    .trend-badge.up { background: #dcfce7; color: #15803d; }
    .trend-badge.down { background: #fee2e2; color: #dc2626; }
    
    .product-rank {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .product-rank:hover {
        background: #f8fafc;
    }
    .product-rank .rank-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .product-rank .rank-number.top1 { background: #fef3c7; color: #b45309; }
    .product-rank .rank-number.top2 { background: #e5e7eb; color: #4b5563; }
    .product-rank .rank-number.top3 { background: #fed7aa; color: #9a3412; }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .stat-card .icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        .stat-value {
            font-size: 20px;
        }
        #salesChart {
            height: 180px !important;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Dashboard</h1>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}! Ringkasan bisnis hari ini.</p>
        </div>
        <div>
            <span class="badge bg-primary p-3 fs-6 rounded-pill">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Penjualan Hari Ini</span>
                            <div class="stat-value mt-1">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
                            <span class="trend-badge up mt-2 d-inline-block">
                                <i class="fas fa-arrow-up me-1"></i>12.5%
                            </span>
                        </div>
                        <div class="icon-wrapper blue">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Pengeluaran Hari Ini</span>
                            <div class="stat-value mt-1">Rp {{ number_format($expensesToday, 0, ',', '.') }}</div>
                            <span class="trend-badge down mt-2 d-inline-block">
                                <i class="fas fa-arrow-down me-1"></i>3.2%
                            </span>
                        </div>
                        <div class="icon-wrapper red">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Keuntungan Bulan Ini</span>
                            <div class="stat-value mt-1">Rp {{ number_format($profitThisMonth, 0, ',', '.') }}</div>
                            <span class="trend-badge up mt-2 d-inline-block">
                                <i class="fas fa-arrow-up me-1"></i>8.7%
                            </span>
                        </div>
                        <div class="icon-wrapper green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Total Produk</span>
                            <div class="stat-value mt-1">{{ number_format($totalProducts) }}</div>
                            <span class="trend-badge up mt-2 d-inline-block">
                                <i class="fas fa-plus me-1"></i>4 baru
                            </span>
                        </div>
                        <div class="icon-wrapper orange">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-xl-8 col-lg-7 col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Tren Penjualan 12 Bulan
                    </h6>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        <i class="fas fa-arrow-up me-1"></i>+12.5%
                    </span>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="220"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 col-12">
            <!-- Produk Terlaris -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-fire me-2 text-danger"></i>Produk Terlaris
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $index => $product)
                    <div class="product-rank">
                        <span class="rank-number {{ $index == 0 ? 'top1' : ($index == 1 ? 'top2' : ($index == 2 ? 'top3' : '')) }}">
                            {{ $loop->iteration }}
                        </span>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">{{ $product->name }}</h6>
                            <small class="text-muted">Terjual {{ $product->total_sold ?? 0 }} pcs</small>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary">Rp {{ number_format($product->selling_price) }}</span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-3x text-muted opacity-25 mb-2 d-block"></i>
                        <p class="text-muted">Belum ada data penjualan</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($lowStockProducts as $product)
                    <div class="product-rank">
                        <div class="bg-light rounded-3 p-2 me-2">
                            <i class="fas fa-box text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">{{ $product->name }}</h6>
                            <small class="text-muted">Sisa {{ $product->stock }} pcs</small>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning">Menipis</span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success opacity-25 mb-2 d-block"></i>
                        <p class="text-muted">Semua stok aman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    let monthlyData = @json($monthlySales ?? []);
    
    if (monthlyData.length === 0) {
        monthlyData = [
            { month: 'Jan 2024', sales: 0, profit: 0 },
            { month: 'Feb 2024', sales: 0, profit: 0 },
            { month: 'Mar 2024', sales: 0, profit: 0 },
            { month: 'Apr 2024', sales: 0, profit: 0 },
            { month: 'May 2024', sales: 0, profit: 0 },
            { month: 'Jun 2024', sales: 0, profit: 0 },
            { month: 'Jul 2024', sales: 0, profit: 0 },
            { month: 'Aug 2024', sales: 0, profit: 0 },
            { month: 'Sep 2024', sales: 0, profit: 0 },
            { month: 'Oct 2024', sales: 0, profit: 0 },
            { month: 'Nov 2024', sales: 0, profit: 0 },
            { month: 'Dec 2024', sales: 0, profit: 0 }
        ];
    }
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(26, 42, 74, 0.3)');
    gradient.addColorStop(1, 'rgba(26, 42, 74, 0)');
    
    const gradientProfit = ctx.createLinearGradient(0, 0, 0, 400);
    gradientProfit.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
    gradientProfit.addColorStop(1, 'rgba(34, 197, 94, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Penjualan',
                    data: monthlyData.map(d => d.sales),
                    borderColor: '#1a2a4a',
                    backgroundColor: gradient,
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
                    label: 'Keuntungan',
                    data: monthlyData.map(d => d.profit),
                    borderColor: '#22c55e',
                    backgroundColor: gradientProfit,
                    borderWidth: 3,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#22c55e',
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
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
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
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection