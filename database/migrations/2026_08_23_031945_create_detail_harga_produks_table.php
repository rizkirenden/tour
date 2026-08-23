<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_harga_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk_pakets', 'id_produk')->onDelete('cascade');
            $table->foreignId('id_jenis_transaksi')->constrained('jenis_transaksis', 'id_jenis')->onDelete('cascade');
            $table->bigInteger('harga')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['id_produk', 'id_jenis_transaksi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_harga_produk');
    }
};
