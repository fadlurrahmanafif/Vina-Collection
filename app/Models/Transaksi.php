<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    public $timestamps = true;

    protected $fillable = [
        'code_transaksi',
        'total_qty',
        'total_harga',
        'nama_pelanggan',  // sudah direname dari nama_costumer
        'alamat',
        'no_telp',        // sudah direname dari no_tlp
        'ekspedisi',
        'metode_pembayaran',
        'subtotal',
        'ongkir',
        'biaya_layanan',
        'total_pembayaran',
        'status_pesanan',
        'id_user',
        'tanggal_pemesanan',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'subtotal' => 'decimal:0',
        'ongkir' => 'decimal:0',
        'biaya_layanan' => 'decimal:0',
        'total_pembayaran' => 'decimal:0',
    ];

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi_code', 'code_transaksi');
    }

    // Method untuk get status badge class
    public function getStatusBadgeClass()
    {
        switch ($this->status_pesanan) {
            case 'pending':
                return 'bg-warning text-dark';
            case 'dikonfirmasi':
                return 'bg-info';
            case 'diproses':
                return 'bg-primary';
            case 'dikirim':
                return 'bg-secondary';
            case 'selesai':
                return 'bg-success';
            case 'dibatalkan':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    // Method untuk get status text
    public function getStatusText()
    {
        switch ($this->status_pesanan) {
            case 'pending':
                return 'Menunggu Konfirmasi';
            case 'dikonfirmasi':
                return 'Dikonfirmasi';
            case 'diproses':
                return 'Sedang Diproses';
            case 'dikirim':
                return 'Dalam Pengiriman';
            case 'selesai':
                return 'Selesai';
            case 'dibatalkan':
                return 'Dibatalkan';
            default:
                return 'Unknown';
        }
    }
}
