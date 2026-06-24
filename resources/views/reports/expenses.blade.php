@extends('layouts.app')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Laporan Pengeluaran</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('reports.expenses') }}" method="GET" class="row g-3">
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
                    <span class="text-muted small fw-semibold text-uppercase">Total Pengeluaran</span>
                    <h4 class="fw-bold text-danger mt-1 mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small fw-semibold text-uppercase">Total Item</span>
                    <h4 class="fw-bold mt-1 mb-0">{{ number_format($totalItems) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('reports.export.expenses-pdf', request()->all()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('reports.export.expenses-excel', request()->all()) }}" class="btn btn-success">
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
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Deskripsi</th>
                            <th class="py-3 text-end">Jumlah</th>
                            <th class="py-3">Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="py-3">{{ \Carbon\Carbon::parse($expense->date)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                            <td class="py-3"><span class="badge bg-primary">{{ $expense->category }}</span></td>
                            <td class="py-3">{{ $expense->description }}</td>
                            <td class="py-3 text-end fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="py-3">{{ $expense->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-wallet fa-3x text-muted mb-3 d-block opacity-25"></i>
                                <h5 class="text-muted">Belum ada pengeluaran</h5>
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