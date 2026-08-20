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
            $table->foreignId('hotel_mekkah_default')->nullable()->constrained('hotels', 'id_hotel');
            $table->foreignId('hotel_madinah_default')->nullable()->constrained('hotels', 'id_hotel');
            $table->foreignId('hotel_transit_default')->nullable()->constrained('hotels', 'id_hotel');
            $table->boolean('include_tur')->default(0);
            $table->foreignId('paket_tour_id')->nullable()->constrained('paket_tours', 'id_paket_tour')->onDelete('set null');
            $table->foreignId('status_keberangkatan_id')->nullable()->constrained('status_keberangkatans', 'id_status')->onDelete('set null');
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