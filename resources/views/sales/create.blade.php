@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<style>
    .product-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .product-item:hover {
        background: #f8f9fa;
        border-color: #1a2a4a !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .cart-item {
        border-bottom: 1px solid #e9ecef;
        padding: 8px 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-container {
        max-height: 350px;
        overflow-y: auto;
    }
    .cart-qty-input {
        width: 50px;
        text-align: center;
        padding: 2px;
        font-size: 13px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    .cart-qty-input:focus {
        outline: none;
        border-color: #1a2a4a;
        box-shadow: 0 0 0 2px rgba(26,42,74,0.1);
    }
    .cart-qty-btn {
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        min-width: 28px;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark">Kasir</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
        </div>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Riwayat
        </a>
    </div>

    <div class="row g-4">
        <!-- Produk -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <input type="text" class="form-control mb-3" id="searchProduct" 
                           placeholder="🔍 Cari produk... (Nama / Barcode)">
                    
                    <div class="row g-2" id="productGrid">
                        @forelse($products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-item border rounded p-2 text-center" 
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->selling_price }}"
                                 data-stock="{{ $product->stock }}">
                                <div class="bg-light rounded-3 p-2 mb-2">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <div class="small fw-bold mt-1">{{ Str::limit($product->name, 18) }}</div>
                                <div class="text-primary fw-bold">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                <small class="text-muted">Stok: {{ $product->stock }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada produk yang tersedia</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Keranjang -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-shopping-cart me-2"></i>Keranjang
                        </h5>
                        <span class="badge bg-primary" id="cartCount">0</span>
                    </div>
                </div>
                <div class="card-body cart-container" id="cartContainer">
                    <div id="cartItems">
                        <p class="text-muted text-center py-4">Belum ada produk</p>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold text-primary fs-5" id="grandTotal">Rp 0</span>
                    </div>
                    
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                        @csrf
                        <input type="hidden" name="products" id="productsInput">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Pembayaran</label>
                            <input type="number" class="form-control" id="payment" 
                                   name="payment" placeholder="0" min="0" required>
                            <small class="text-muted">Kembalian: <span id="changeDisplay" class="fw-bold">Rp 0</span></small>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="saveBtn">
                            <i class="fas fa-save me-2"></i>Simpan Transaksi
                        </button>
                        <button type="button" class="btn btn-danger w-100 mt-2" id="clearCart">
                            <i class="fas fa-trash me-2"></i>Kosongkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var cart = [];

// ============================================
// 🛒 KLIK PRODUK - TAMBAH 1
// ============================================

document.querySelectorAll('.product-item').forEach(function(el) {
    el.addEventListener('click', function() {
        var id = parseInt(this.dataset.id);
        var name = this.dataset.name;
        var price = parseInt(this.dataset.price);
        var stock = parseInt(this.dataset.stock);
        
        var existing = null;
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                existing = cart[i];
                break;
            }
        }
        
        if (existing) {
            if (existing.qty < stock) {
                existing.qty++;
            } else {
                alert('⚠️ Stok tidak mencukupi! (Stok: ' + stock + ')');
                return;
            }
        } else {
            if (stock < 1) {
                alert('⚠️ Stok habis!');
                return;
            }
            cart.push({ id: id, name: name, price: price, qty: 1, maxStock: stock });
        }
        updateCart();
    });
});

// ============================================
// 🛒 UPDATE CART
// ============================================

function updateCart() {
    var container = document.getElementById('cartItems');
    var totalEl = document.getElementById('grandTotal');
    var countEl = document.getElementById('cartCount');
    var html = '';
    var total = 0;
    var count = 0;
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-muted text-center py-4">Belum ada produk</p>';
        totalEl.textContent = 'Rp 0';
        countEl.textContent = '0';
        document.getElementById('productsInput').value = '';
        updatePayment();
        return;
    }
    
    cart.forEach(function(item, index) {
        var subtotal = item.price * item.qty;
        total += subtotal;
        count += item.qty;
        
        html += '<div class="cart-item d-flex justify-content-between align-items-center">';
        html += '<div><div class="fw-bold">' + item.name + '</div>';
        html += '<small>Rp ' + item.price.toLocaleString() + '</small></div>';
        html += '<div class="d-flex align-items-center gap-1">';
        
        // Tombol -
        html += '<button class="btn btn-sm btn-outline-secondary cart-qty-btn cart-qty-minus" data-index="' + index + '">';
        html += '<i class="fas fa-minus"></i></button>';
        
        // Input qty
        html += '<input type="number" class="form-control form-control-sm cart-qty-input" ';
        html += 'value="' + item.qty + '" min="1" max="' + item.maxStock + '" ';
        html += 'data-index="' + index + '">';
        
        // Tombol +
        html += '<button class="btn btn-sm btn-outline-secondary cart-qty-btn cart-qty-plus" data-index="' + index + '">';
        html += '<i class="fas fa-plus"></i></button>';
        
        // Subtotal
        html += '<span class="fw-bold text-primary ms-1" style="min-width:70px;text-align:right;">';
        html += 'Rp ' + subtotal.toLocaleString() + '</span>';
        
        // Hapus
        html += '<button class="btn btn-sm btn-danger ms-1" onclick="removeItem(' + index + ')">';
        html += '<i class="fas fa-times"></i></button>';
        html += '</div></div>';
    });
    
    container.innerHTML = html;
    totalEl.textContent = 'Rp ' + total.toLocaleString();
    countEl.textContent = count;
    document.getElementById('productsInput').value = JSON.stringify(cart);
    updatePayment();
    
    // ============================================
    // 🔥 EVENT LISTENER UNTUK KERANJANG
    // ============================================
    
    // Input qty - ketik angka langsung
    document.querySelectorAll('.cart-qty-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var index = parseInt(this.dataset.index);
            var val = parseInt(this.value) || 1;
            var maxStock = cart[index] ? cart[index].maxStock : 999;
            
            if (val < 1) {
                this.value = 1;
                val = 1;
            } else if (val > maxStock) {
                this.value = maxStock;
                val = maxStock;
                alert('⚠️ Stok maksimal: ' + maxStock);
            }
            
            if (cart[index]) {
                cart[index].qty = val;
                updateCart();
            }
        });
        
        // Enter key di input qty
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
    });
    
    // Tombol + di keranjang
    document.querySelectorAll('.cart-qty-plus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var index = parseInt(this.dataset.index);
            if (cart[index]) {
                var maxStock = cart[index].maxStock || 999;
                if (cart[index].qty < maxStock) {
                    cart[index].qty++;
                    updateCart();
                } else {
                    alert('⚠️ Stok maksimal: ' + maxStock);
                }
            }
        });
    });
    
    // Tombol - di keranjang
    document.querySelectorAll('.cart-qty-minus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var index = parseInt(this.dataset.index);
            if (cart[index] && cart[index].qty > 1) {
                cart[index].qty--;
                updateCart();
            } else if (cart[index] && cart[index].qty === 1) {
                removeItem(index);
            }
        });
    });
}

// ============================================
// 🗑️ REMOVE ITEM
// ============================================

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

// ============================================
// 🗑️ CLEAR CART
// ============================================

document.getElementById('clearCart').addEventListener('click', function() {
    if (cart.length === 0) return;
    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        updateCart();
    }
});

// ============================================
// 🔍 SEARCH
// ============================================

document.getElementById('searchProduct').addEventListener('input', function() {
    var keyword = this.value.toLowerCase().trim();
    document.querySelectorAll('.product-item').forEach(function(el) {
        var name = el.dataset.name.toLowerCase();
        el.style.display = name.includes(keyword) ? '' : 'none';
    });
});

// ============================================
// 💳 PAYMENT & SUBMIT
// ============================================

function updatePayment() {
    var payment = parseInt(document.getElementById('payment').value) || 0;
    var totalText = document.getElementById('grandTotal').textContent;
    var total = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;
    var change = payment - total;
    var changeDisplay = document.getElementById('changeDisplay');
    changeDisplay.textContent = 'Rp ' + (change >= 0 ? change.toLocaleString() : '0');
    changeDisplay.style.color = change >= 0 ? 'green' : 'red';
    
    var saveBtn = document.getElementById('saveBtn');
    if (cart.length === 0) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Keranjang Kosong';
    } else if (change < 0) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Pembayaran Kurang';
    } else {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Transaksi';
    }
}

document.getElementById('payment').addEventListener('input', updatePayment);

// Submit form
document.getElementById('saleForm').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('⚠️ Keranjang kosong!');
        return false;
    }
    var payment = parseInt(document.getElementById('payment').value) || 0;
    var totalText = document.getElementById('grandTotal').textContent;
    var total = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;
    if (payment < total) {
        e.preventDefault();
        alert('⚠️ Pembayaran kurang!');
        return false;
    }
});

// ============================================
// 🚀 INIT
// ============================================
updateCart();
</script>
@endpush
@endsection