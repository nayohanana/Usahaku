@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Pengeluaran</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pengeluaran
        </a>
    </div>

    <!-- Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Pengeluaran</h6>
                    <h3 class="fw-bold text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Filter</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary"><i class="fas fa-undo me-2"></i>Reset</a>
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
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Deskripsi</th>
                            <th class="py-3">Jumlah</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $index => $expense)
                        <tr>
                            <td class="px-4 py-3">{{ $expenses->firstItem() + $index }}</td>
                            <td class="py-3">{{ \Carbon\Carbon::parse($expense->date)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                            <td class="py-3"><span class="badge bg-primary">{{ $expense->category }}</span></td>
                            <td class="py-3">{{ $expense->description }}</td>
                            <td class="py-3 fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="py-3 text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-wallet fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Belum ada pengeluaran</h5>
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus me-2"></i>Tambah Pengeluaran</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $expenses->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection