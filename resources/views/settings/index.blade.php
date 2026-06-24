@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<style>
    .setting-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .setting-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
    }
    .setting-card .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .setting-card .icon-box.blue { background: rgba(26, 42, 74, 0.1); color: #1a2a4a; }
    .setting-card .icon-box.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .setting-card .icon-box.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .setting-card .icon-box.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .setting-card .icon-box.purple { background: rgba(168, 85, 247, 0.1); color: #8b5cf6; }
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Pengaturan</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profil Toko -->
        <div class="col-lg-6">
            <div class="card setting-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box blue">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Profil Toko</h5>
                            <small class="text-muted">Informasi toko Anda</small>
                        </div>
                    </div>

                    <form action="{{ route('settings.update-profile') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Toko</label>
                            <input type="text" class="form-control" name="store_name" value="{{ $storeName }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control" name="store_address" rows="2">{{ $storeAddress }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Telepon</label>
                                <input type="text" class="form-control" name="store_phone" value="{{ $storePhone }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="store_email" value="{{ $storeEmail }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Aplikasi -->
        <div class="col-lg-6">
            <div class="card setting-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box purple">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Info Aplikasi</h5>
                            <small class="text-muted">Versi dan informasi aplikasi</small>
                        </div>
                    </div>

                    <table class="table">
                        <tr>
                            <th style="width: 140px;">Nama Aplikasi</th>
                            <td>: <span class="fw-semibold">{{ $storeName }}</span></td>
                        </tr>
                        <tr>
                            <th>Versi</th>
                            <td>: <span class="fw-semibold">{{ $appVersion }}</span></td>
                        </tr>
                        <tr>
                            <th>Developer</th>
                            <td>: {{ $appDeveloper }}</td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>: <a href="{{ $appWebsite }}" target="_blank">{{ $appWebsite }}</a></td>
                        </tr>
                        <tr>
                            <th>PHP Version</th>
                            <td>: {{ phpversion() }}</td>
                        </tr>
                        <tr>
                            <th>Laravel Version</th>
                            <td>: {{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Server</th>
                            <td>: {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Database -->
        <div class="col-lg-6">
            <div class="card setting-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box green">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Database</h5>
                            <small class="text-muted">Info dan backup database</small>
                        </div>
                    </div>

                    <table class="table">
                        <tr>
                            <th style="width: 140px;">Total User</th>
                            <td>: <span class="fw-semibold">{{ $totalUsers }}</span></td>
                        </tr>
                        <tr>
                            <th>Total Produk</th>
                            <td>: <span class="fw-semibold">{{ $totalProducts }}</span></td>
                        </tr>
                        <tr>
                            <th>Total Penjualan</th>
                            <td>: <span class="fw-semibold">{{ $totalSales }}</span></td>
                        </tr>
                        <tr>
                            <th>Total Pengeluaran</th>
                            <td>: <span class="fw-semibold">{{ $totalExpenses }}</span></td>
                        </tr>
                        <tr>
                            <th>Ukuran Database</th>
                            <td>: <span class="fw-semibold">{{ $dbSize }}</span></td>
                        </tr>
                    </table>

                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('settings.backup') }}" class="btn btn-success flex-grow-1">
                            <i class="fas fa-download me-2"></i>Backup Database
                        </a>
                        <a href="{{ route('settings.clear-cache') }}" class="btn btn-warning flex-grow-1">
                            <i class="fas fa-broom me-2"></i>Clear Cache
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Aktivitas -->
        <div class="col-lg-6">
            <div class="card setting-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box orange">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Log Aktivitas</h5>
                            <small class="text-muted">Riwayat aktivitas terakhir</small>
                        </div>
                    </div>

                    @if(count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="d-flex align-items-start gap-3 border-bottom py-2">
                            <div class="bg-light rounded-3 p-2">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $activity['user'] }}</div>
                                <div class="text-muted small">{{ $activity['action'] }}</div>
                            </div>
                            <div class="text-muted small">{{ $activity['time'] }}</div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection