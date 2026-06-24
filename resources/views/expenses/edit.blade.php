@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Edit Pengeluaran</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Operasional" {{ old('category', $expense->category) == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Listrik" {{ old('category', $expense->category) == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="Air" {{ old('category', $expense->category) == 'Air' ? 'selected' : '' }}>Air</option>
                            <option value="Internet" {{ old('category', $expense->category) == 'Internet' ? 'selected' : '' }}>Internet</option>
                            <option value="Transportasi" {{ old('category', $expense->category) == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="Gaji" {{ old('category', $expense->category) == 'Gaji' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="Sewa" {{ old('category', $expense->category) == 'Sewa' ? 'selected' : '' }}>Sewa Tempat</option>
                            <option value="Perawatan" {{ old('category', $expense->category) == 'Perawatan' ? 'selected' : '' }}>Perawatan</option>
                            <option value="Lainnya" {{ old('category', $expense->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description', $expense->description) }}" required>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $expense->amount) }}" required>
                        </div>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method">
                            <option value="">Pilih Metode</option>
                            <option value="Tunai" {{ old('payment_method', $expense->payment_method) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="Transfer" {{ old('payment_method', $expense->payment_method) == 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="E-Wallet" {{ old('payment_method', $expense->payment_method) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="Kartu Kredit" {{ old('payment_method', $expense->payment_method) == 'Kartu Kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection