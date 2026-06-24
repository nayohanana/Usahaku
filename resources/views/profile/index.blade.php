@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<style>
    .profile-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
    }
    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin: 0 auto;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-item .label {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 500;
    }
    .info-item .value {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Profile</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </a>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card profile-card shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="avatar-wrapper">
                        @if($user->avatar)
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=1a2a4a&color=fff&size=120" alt="{{ $user->name }}">
                        @endif
                    </div>
                    <h4 class="fw-bold mt-3">{{ $user->name }}</h4>
                    <p class="text-muted">
                        <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Bergabung sejak {{ $user->created_at->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Detail -->
        <div class="col-lg-8">
            <div class="card profile-card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Profile
                    </h5>
                    
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Nama Lengkap</span>
                        <span class="value">{{ $user->name }}</span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Email</span>
                        <span class="value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Role</span>
                        <span class="value"><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Telepon</span>
                        <span class="value">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Alamat</span>
                        <span class="value">{{ $user->address ?? '-' }}</span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Terdaftar</span>
                        <span class="value">{{ $user->created_at->format('d F Y H:i') }} WIB</span>
                    </div>
                    <div class="info-item d-flex justify-content-between">
                        <span class="label">Terakhir Update</span>
                        <span class="value">{{ $user->updated_at->format('d F Y H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="card profile-card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-chart-simple me-2 text-success"></i>Statistik Akun
                    </h5>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h4 class="fw-bold text-primary">{{ $user->sales->count() }}</h4>
                                <small class="text-muted">Transaksi Penjualan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h4 class="fw-bold text-danger">{{ $user->expenses->count() }}</h4>
                                <small class="text-muted">Pengeluaran</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h4 class="fw-bold text-success">Rp {{ number_format($user->sales->sum('grand_total') ?? 0, 0, ',', '.') }}</h4>
                                <small class="text-muted">Total Penjualan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection