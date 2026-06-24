@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Detail Supplier</h1>
            <p class="text-muted">Informasi lengkap supplier</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Informasi Supplier</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 140px;">Nama</th>
                            <td>: {{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $supplier->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $supplier->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $supplier->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>: {{ $supplier->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $supplier->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Riwayat Stok Masuk</h5>
                    @if($supplier->stockIns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Invoice</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->stockIns as $stock)
                                <tr>
                                    <td>{{ $stock->date->format('d M Y') }}</td>
                                    <td>{{ $stock->invoice_number }}</td>
                                    <td>Rp {{ number_format($stock->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Belum ada transaksi stok masuk</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection