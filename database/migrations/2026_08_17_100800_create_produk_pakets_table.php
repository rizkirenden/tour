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
            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('include_tur')->default(0);
            $table->foreignId('paket_tour_id')->nullable()->constrained('paket_tours', 'id_paket_tour')->onDelete('set null');

            // HARGA DASAR (INTEGER - tanpa desimal)
            $table->integer('harga_dasar')->default(0)->comment('Harga dasar produk dalam Rupiah');

            // Durasi
            $table->integer('durasi_perjalanan')->nullable()->comment('Durasi total perjalanan dalam hari');
            $table->integer('durasi_mekkah')->default(4)->comment('Durasi di Mekkah dalam hari');
            $table->integer('durasi_madinah')->default(4)->comment('Durasi di Madinah dalam hari');
            $table->integer('durasi_hari')->nullable()->comment('Total durasi dalam hari (auto calculate)');

            // Flyer
            $table->string('flyer')->nullable()->comment('Path file flyer produk');

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
