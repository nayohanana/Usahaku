<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'barcode',
        'purchase_price', 'selling_price', 'stock', 'min_stock',
        'image', 'description', 'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockIns()
    {
        return $this->hasMany(StockInDetail::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) return 'habis';
        if ($this->stock <= $this->min_stock) return 'menipis';
        return 'aman';
    }

    public function getProfitAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name) . '-' . uniqid();
            if (empty($product->barcode)) {
                $product->barcode = 'PRD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });

        // 🔔 AUTO CREATE NOTIFIKASI STOK MENIPIS
        static::updated(function ($product) {
            if ($product->stock <= $product->min_stock && $product->stock > 0) {
                \App\Http\Controllers\NotificationController::createStockAlert($product);
            }
        });
    }
}