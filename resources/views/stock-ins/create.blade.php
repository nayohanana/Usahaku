@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Tambah Stok Masuk</h1>
            <p class="text-muted">Catat pembelian barang dari supplier</p>
        </div>
        <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('stock-ins.store') }}" method="POST" id="stockInForm">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" name="supplier_id" required>
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Keterangan</label>
                            <input type="text" class="form-control @error('notes') is-invalid @enderror" 
                                   id="notes" name="notes" value="{{ old('notes') }}" placeholder="Catatan tambahan">
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Daftar Produk</h5>
                    <button type="button" class="btn btn-success" id="addProduct">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>

                <div id="productList">
                    <div class="product-item mb-3">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Produk</label>
                                <select class="form-select product-select" name="products[0][product_id]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Jumlah</label>
                                <input type="number" class="form-control quantity" name="products[0][quantity]" 
                                       placeholder="0" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Harga</label>
                                <input type="number" class="form-control price" name="products[0][price]" 
                                       placeholder="0" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">&nbsp;</label>
                                <button type="button" class="btn btn-danger w-100 remove-product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Stok Masuk
                    </button>
                    <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let productIndex = 1;

    document.getElementById('addProduct').addEventListener('click', function() {
        const productList = document.getElementById('productList');
        const newItem = document.createElement('div');
        newItem.className = 'product-item mb-3';
        newItem.innerHTML = `
            <div class="row g-3">
                <div class="col-md-5">
                    <select class="form-select product-select" name="products[${productIndex}][product_id]" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control quantity" name="products[${productIndex}][quantity]" 
                           placeholder="0" min="1" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control price" name="products[${productIndex}][price]" 
                           placeholder="0" min="0" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100 remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        productList.appendChild(newItem);
        productIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            const items = document.querySelectorAll('.product-item');
            if (items.length > 1) {
                e.target.closest('.product-item').remove();
            } else {
                alert('Minimal 1 produk harus diisi!');
            }
        }
    });
</script>
@endpush
@endsection