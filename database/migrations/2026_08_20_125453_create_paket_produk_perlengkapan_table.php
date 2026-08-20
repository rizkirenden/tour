<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_produk_perlengkapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_perlengkapan');
            $table->integer('kuantitas')->default(1);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk_pakets')
                  ->onDelete('cascade');

            $table->foreign('id_perlengkapan')
                  ->references('id_perlengkapan')
                  ->on('perlengkapans')
                  ->onDelete('cascade');

            // Unique constraint agar tidak ada duplikasi
            $table->unique(['id_produk', 'id_perlengkapan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_produk_perlengkapan');
    }
};
