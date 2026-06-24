<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'email', 'description'];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }
}