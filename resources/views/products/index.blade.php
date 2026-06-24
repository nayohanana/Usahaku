@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Manajemen Produk</h1>
            <p class="text-muted">Kelola data produk toko Anda</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Produk
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock_status" class="form-select">
                        <option value="">Semua Stok</option>
                        <option value="aman" {{ request('stock_status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="menipis" {{ request('stock_status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="habis" {{ request('stock_status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-light rounded-3 p-2">
                            @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                            @else
                            <i class="fas fa-box fa-2x text-primary"></i>
                            @endif
                        </div>
                        @php
                            $status = $product->stock_status;
                            $badgeClass = $status == 'aman' ? 'bg-success' : ($status == 'menipis' ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    <p class="text-muted small">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="text-muted small">Harga Jual</span>
                            <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small">Stok</span>
                            <h5 class="fw-bold mb-0">{{ $product->stock }}</h5>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm flex-grow-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                        onclick="return confirm('Yakin hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada produk</h4>
                <p class="text-muted">Mulai tambahkan produk pertama Anda</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection