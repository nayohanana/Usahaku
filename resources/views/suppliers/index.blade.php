@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Data Supplier</h1>
            <p class="text-muted">Kelola data supplier / pemasok barang</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Supplier
        </a>
    </div>

    <!-- Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('suppliers.index') }}" method="GET" class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari supplier..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 px-md-4 py-3 text-center" style="width:40px;">#</th>
                            <th class="py-3" style="min-width:120px;">Nama Supplier</th>
                            <th class="py-3 d-none d-sm-table-cell">Telepon</th>
                            <th class="py-3 d-none d-md-table-cell">Email</th>
                            <th class="py-3 d-none d-lg-table-cell">Alamat</th>
                            <th class="py-3 text-center" style="min-width:110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                        <tr>
                            <td class="px-2 px-md-4 py-3 text-center">{{ $suppliers->firstItem() + $index }}</td>
                            <td class="py-3 fw-semibold small">{{ $supplier->name }}</td>
                            <td class="py-3 d-none d-sm-table-cell">{{ $supplier->phone ?? '-' }}</td>
                            <td class="py-3 d-none d-md-table-cell">{{ $supplier->email ?? '-' }}</td>
                            <td class="py-3 d-none d-lg-table-cell">{{ Str::limit($supplier->address, 20) ?? '-' }}</td>
                            <td class="py-3 text-center">
                                <div class="btn-action-group">
                                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Yakin hapus supplier ini?')"
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
                                <i class="fas fa-truck fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Belum ada data supplier</h5>
                                <p class="text-muted">Mulai tambahkan supplier pertama Anda</p>
                                <a href="{{ route('suppliers.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>Tambah Supplier
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
                <span class="text-muted">Total: {{ $suppliers->total() }} supplier</span>
                <div class="mt-2 mt-sm-0">
                    {{ $suppliers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection