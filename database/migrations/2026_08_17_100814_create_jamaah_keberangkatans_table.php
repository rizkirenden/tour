<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_keberangkatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jamaah');
            $table->unsignedBigInteger('id_produk_keberangkatan');
            $table->unsignedBigInteger('id_produk_harga_bulanan')->nullable();

            // Bulan & tahun keberangkatan (dari produk_keberangkatans)
            $table->integer('bulan_keberangkatan');
            $table->integer('tahun_keberangkatan');

            // Bulan & tahun harga (dari produk_harga_bulanan)
            $table->integer('bulan_harga')->nullable();
            $table->integer('tahun_harga')->nullable();

            // Harga yang dipilih
            $table->bigInteger('harga')->default(0);

            $table->timestamps();

            $table->foreign('id_jamaah')
                  ->references('id_jamaah')
                  ->on('jamaahs')
                  ->onDelete('cascade');

            $table->foreign('id_produk_keberangkatan')
                  ->references('id')
                  ->on('produk_keberangkatans')
                  ->onDelete('cascade');

            $table->foreign('id_produk_harga_bulanan')
                  ->references('id')
                  ->on('produk_harga_bulanan')
                  ->onDelete('set null');

            $table->index(['id_jamaah', 'bulan_keberangkatan', 'tahun_keberangkatan'], 'idx_jamaah_keberangkatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_keberangkatans');
    }
};
