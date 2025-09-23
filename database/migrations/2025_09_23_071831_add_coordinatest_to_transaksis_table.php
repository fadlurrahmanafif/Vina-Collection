<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->decimal('latitude' , 10 ,8)->after('alamat')
            ->comment('Koordinat latitude lokasi pengiriman (Wajib)');
            $table->decimal('longitude', 11 , 8)->after('latitude')
            ->comment('Koordinat longitude lokasi pengiriman (Wajib)');

            $table->text('alamat_detail')->nullable()->after('longitude')
            ->comment('Alamat lengkap dari user');

            $table->string('provinsi' , 100)->default('Jawa Timur')->after('alamat_detail');
            $table->string('kecamatan', 100)->after('provinsi');
            $table->string('kota', 100)->default('Bondowoso')->after('kecamatan');
            $table->string('kode_pos', 10)->after('kota');

            $table->decimal('estimasi_jarak_km' , 8, 2)->after('kode_pos')
            ->comment('Estimasi dari jarak toko ke lokasi pengiriman dalam KM');

            $table->index(['latitude','longitude'], 'idx_coordinates');
            $table->index(['kota', 'provinsi'], 'idx_location');
        });
    }

   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex('idx_coordinates');
            $table->dropIndex('idx_location');

            $table->dropColumn([
                'latitude',
                'longitude',
                'alamat_detail',
                'provinsi',
                'kecamatan',
                'kota',
                'kode_pos',
                'estimasi_jarak_km'
            ]);
        });
    }
};
