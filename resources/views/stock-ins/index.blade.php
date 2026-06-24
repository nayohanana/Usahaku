@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Stok Masuk</h1>
            <p class="text-muted">Catatan pembelian barang dari supplier</p>
        </div>
        <a href="{{ route('stock-ins.create') }}" class="btn btn-primary btn-sm-mobile">
            <i class="fas fa-plus me-1 me-md-2"></i><span class="d-none d-sm-inline">Tambah Stok Masuk</span><span class="d-inline d-sm-none">Tambah</span>
        </a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('stock-ins.index') }}" method="GET" class="row g-2 g-md-3">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm-mobile">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-sm-mobile" 
                               placeholder="Cari invoice..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date_from" class="form-control form-control-sm-mobile" 
                           value="{{ request('date_from') }}" placeholder="Dari">
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date_to" class="form-control form-control-sm-mobile" 
                           value="{{ request('date_to') }}" placeholder="Sampai">
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100 btn-sm-mobile">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-striped-mobile">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 px-md-4 py-2 py-md-3 text-center" style="width:35px;">#</th>
                            <th class="py-2 py-md-3" style="min-width:120px;">Invoice</th>
                            <th class="py-2 py-md-3 d-none d-sm-table-cell">Supplier</th>
                            <th class="py-2 py-md-3" style="min-width:120px;">Tanggal</th>
                            <th class="py-2 py-md-3 text-end" style="min-width:100px;">Total</th>
                            <th class="py-2 py-md-3 text-center" style="min-width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $index => $stock)
                        <tr>
                            <td class="px-2 px-md-4 py-2 py-md-3 text-center">{{ $stockIns->firstItem() + $index }}</td>
                            <td class="py-2 py-md-3 fw-semibold small">{{ $stock->invoice_number }}</td>
                            <td class="py-2 py-md-3 d-none d-sm-table-cell">{{ $stock->supplier->name }}</td>
                            <td class="py-2 py-md-3 small">{{ \Carbon\Carbon::parse($stock->date)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                            <td class="py-2 py-md-3 text-end fw-bold text-primary small">Rp {{ number_format($stock->total_price, 0, ',', '.') }}</td>
                            <td class="py-2 py-md-3 text-center">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <a href="{{ route('stock-ins.show', $stock) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('stock-ins.print', $stock) }}" class="btn btn-sm btn-outline-info" target="_blank" title="Cetak">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('stock-ins.destroy', $stock) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Yakin hapus?')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-boxes fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Belum ada data</h5>
                                <a href="{{ route('stock-ins.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>Tambah Stok Masuk
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <span class="text-muted small">Total: {{ $stockIns->total() }} transaksi</span>
                <div class="mt-2 mt-sm-0">
                    {{ $stockIns->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 576px) {
        .btn-sm-mobile {
            font-size: 12px;
            padding: 6px 12px;
        }
        .form-control-sm-mobile {
            font-size: 12px;
            padding: 6px 10px;
        }
        .input-group-sm-mobile .input-group-text {
            padding: 6px 10px;
            font-size: 12px;
        }
        .table td, .table th {
            font-size: 11px;
            padding: 6px 4px;
        }
        .btn-sm {
            padding: 3px 6px;
            font-size: 10px;
        }
        .btn-sm i {
            font-size: 10px;
        }
        .pagination .page-link {
            padding: 4px 8px;
            font-size: 12px;
        }
        .table-striped-mobile tbody tr:nth-child(odd) {
            background-color: rgba(0,0,0,0.02);
        }
        .card-footer {
            padding: 10px 12px;
        }
        .text-muted.small {
            font-size: 11px;
        }
    }
</style>
@endsection