@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Detail Produk</h1>
            <p class="text-muted">Informasi lengkap produk</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    @if($product->image)
                    <img src="{{ asset('storage/products/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         style="width: 200px; height: 200px; object-fit: cover; border-radius: 12px;">
                    @else
                    <div class="bg-light rounded-3 p-5">
                        <i class="fas fa-box fa-5x text-muted"></i>
                    </div>
                    @endif
                    
                    <h4 class="mt-3 fw-bold">{{ $product->name }}</h4>
                    <p class="text-muted">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                    
                    @php
                        $status = $product->stock_status;
                        $badgeClass = $status == 'aman' ? 'bg-success' : ($status == 'menipis' ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Produk</h5>
                    <table class="table">
                        <tr>
                            <th style="width: 180px;">Nama Produk</th>
                            <td>: {{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: {{ $product->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Barcode</th>
                            <td>: {{ $product->barcode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Harga Modal</th>
                            <td>: Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>: Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Keuntungan</th>
                            <td>: <span class="text-success">Rp {{ number_format($product->selling_price - $product->purchase_price, 0, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>: {{ $product->stock }}</td>
                        </tr>
                        <tr>
                            <th>Minimum Stok</th>
                            <td>: {{ $product->min_stock }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>: {{ $product->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: {{ $product->is_active ? 'Aktif' : 'Non-Aktif' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $product->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection