@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Detail Pengeluaran</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Edit</a>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table">
                <tr><th style="width:180px;">Kategori</th><td>: <span class="badge bg-primary">{{ $expense->category }}</span></td></tr>
                <tr><th>Deskripsi</th><td>: {{ $expense->description }}</td></tr>
                <tr><th>Jumlah</th><td>: <span class="fw-bold text-danger fs-5">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span></td></tr>
                <tr><th>Tanggal</th><td>: {{ $expense->date->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</td></tr>
                <tr><th>Metode Pembayaran</th><td>: {{ $expense->payment_method ?? '-' }}</td></tr>
                <tr><th>Dicatat oleh</th><td>: {{ $expense->user->name }}</td></tr>
                <tr><th>Catatan</th><td>: {{ $expense->notes ?? '-' }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection