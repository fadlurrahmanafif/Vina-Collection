<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_barang',
        'stok',
        'harga',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_barang', 'id');
    }
}
