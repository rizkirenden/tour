<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_tours', function (Blueprint $table) {
            $table->id('id_paket_tour');
            $table->unsignedBigInteger('id_produk');
            $table->string('kota_tujuan', 50)->nullable();
            $table->string('negara', 50)->nullable();
            $table->integer('durasi_hari')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('harga_include')->default(1);
            $table->bigInteger('harga_tambahan')->nullable();
            $table->bigInteger('harga_per_orang')->nullable();
            $table->timestamps();

            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk_pakets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_tours');
    }
};
