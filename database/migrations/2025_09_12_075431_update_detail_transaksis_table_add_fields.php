<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            // Tambah kolom yang diperlukan
            $table->string('nama_barang')->nullable()->after('id_barang');
            $table->decimal('harga_satuan', 12, 0)->nullable()->after('nama_barang');

            // Update foreign key relationship
            $table->string('id_transaksi_code')->nullable()->after('id_transaksi');
            $table->index('id_transaksi_code');
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropColumn(['nama_barang', 'harga_satuan', 'id_transaksi_code']);
        });
    }
};
