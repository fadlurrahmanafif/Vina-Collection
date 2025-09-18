<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'kategori',
        'tipe',
        'harga',
        'foto',
        'stok',
        'stok_out'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/produk/' . $this->foto) : null;
    }

    // public function product()
    // {
    //     return $this->hasOne(Cart::class,'','id');
    // }
}