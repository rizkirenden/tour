<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_harga_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_paket_id')->constrained('produk_pakets', 'id_produk')->onDelete('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('harga')->default(0);
            $table->string('flyer')->nullable()->comment('Path file flyer untuk harga bulanan ini');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['produk_paket_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_harga_bulanan');
    }
};
