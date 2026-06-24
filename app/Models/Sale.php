<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'date',
        'total_price',
        'discount',
        'grand_total',
        'payment',
        'change',
        'notes',
        'status'
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'payment' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $sale->invoice_number = self::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';

        $lastSale = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->invoice_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $invoiceNumber = $prefix . $newNumber;

        while (self::where('invoice_number', $invoiceNumber)->exists()) {
            $newNumber = str_pad((int) $newNumber + 1, 3, '0', STR_PAD_LEFT);
            $invoiceNumber = $prefix . $newNumber;
        }

        return $invoiceNumber;
    }
}