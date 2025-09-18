<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    public $timestamps = true;
    protected $table = 'detail_transaksis';

    protected $fillable = [
        'id_transaksi',
        'id_transaksi_code',  // untuk relasi dengan code_transaksi
        'id_barang',
        'nama_barang',
        'harga_satuan',
        'status',
        'stok',
        'harga',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:0',
        'harga' => 'decimal:0',
    ];

    // Relasi ke transaksi menggunakan code_transaksi
    public function transaksi()
    {
        return $this->belongsTo(transaksi::class, 'id_transaksi_code', 'code_transaksi');
    }

    // Relasi ke product - PERBAIKI relasi
    public function products()
    {
        return $this->belongsTo(Product::class, 'id_barang', 'id');
    }

        public function product()
    {
        return $this->belongsTo(Product::class, 'id_barang', 'id');
    }
}
