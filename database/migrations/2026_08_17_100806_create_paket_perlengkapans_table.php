<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_perlengkapans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_perlengkapan');
            $table->integer('jumlah')->default(1);
            $table->boolean('wajib')->default(1);
            $table->timestamps();

            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk_pakets')
                  ->onDelete('cascade');

            $table->foreign('id_perlengkapan')
                  ->references('id_perlengkapan')
                  ->on('perlengkapans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_perlengkapans');
    }
};
