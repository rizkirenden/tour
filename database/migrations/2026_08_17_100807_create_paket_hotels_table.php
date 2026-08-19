<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_hotels', function (Blueprint $table) {
            $table->id('id_paket_hotel');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_hotel');
            $table->integer('urutan')->nullable();
            $table->boolean('adalah_default')->default(0);
            $table->string('tipe_penginapan', 50)->nullable();
            $table->bigInteger('harga_per_orang')->nullable();
            $table->timestamps();

            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk_pakets')
                  ->onDelete('cascade');

            $table->foreign('id_hotel')
                  ->references('id_hotel')
                  ->on('hotels')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_hotels');
    }
};
