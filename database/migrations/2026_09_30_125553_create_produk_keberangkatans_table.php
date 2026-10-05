<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_keberangkatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_paket_id')
                ->constrained('produk_pakets', 'id_produk')
                ->onDelete('cascade');
            $table->integer('bulan')->comment('Bulan keberangkatan (1-12)');
            $table->integer('tahun')->comment('Tahun keberangkatan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['produk_paket_id', 'bulan', 'tahun'], 'unique_produk_bulan_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_keberangkatans');
    }
};
