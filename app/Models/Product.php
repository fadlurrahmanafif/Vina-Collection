<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
    ];

    public function cartItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
