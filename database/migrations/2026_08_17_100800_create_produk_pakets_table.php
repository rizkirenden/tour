<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_pakets', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga_dasar');
            $table->string('hotel_mekkah_default', 100)->nullable();
            $table->string('hotel_madinah_default', 100)->nullable();
            $table->string('hotel_transit_default', 100)->nullable();
            $table->boolean('multiple_hotel_enabled')->default(0);
            $table->boolean('include_tur')->default(0);
            $table->integer('kapasitas_kamar_default')->default(4);
            $table->integer('durasi_mekkah')->default(4);
            $table->integer('durasi_madinah')->default(4);
            $table->integer('durasi_transit')->default(1);
            $table->integer('durasi_hari');
            $table->bigInteger('harga_visa')->default(0);
            $table->bigInteger('harga_handling')->default(0);
            $table->bigInteger('harga_muthowwif')->default(0);
            $table->string('kategori', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_pakets');
    }
};
