<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Tambah kolom yang diperlukan
            $table->string('metode_pembayaran')->nullable()->after('ekspedisi');
            $table->decimal('subtotal', 12, 0)->nullable()->after('metode_pembayaran');
            $table->decimal('ongkir', 12, 0)->default(0)->after('subtotal');
            $table->decimal('biaya_layanan', 12, 0)->default(0)->after('ongkir');
            $table->decimal('total_pembayaran', 12, 0)->nullable()->after('biaya_layanan');
            $table->enum('status_pesanan', ['pending', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending')->after('total_pembayaran');
            $table->string('id_user')->default('guest123')->after('status_pesanan');
            $table->timestamp('tanggal_pemesanan')->nullable()->after('id_user');

            // Rename kolom yang sudah ada untuk konsistensi
            $table->renameColumn('nama_costumer', 'nama_pelanggan');
            $table->renameColumn('no_tlp', 'no_telp');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pembayaran',
                'subtotal',
                'ongkir',
                'biaya_layanan',
                'total_pembayaran',
                'status_pesanan',
                'id_user',
                'tanggal_pemesanan'
            ]);

            $table->renameColumn('nama_pelanggan', 'nama_costumer');
            $table->renameColumn('no_telp', 'no_tlp');
        });
    }
};
