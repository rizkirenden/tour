<?php
// database/migrations/xxxx_xx_xx_create_keluargas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id('id_keluarga');
            $table->string('kode_keluarga', 50)->unique();
            $table->string('nama_keluarga', 100);
            $table->string('produk_paket', 100);
            $table->unsignedBigInteger('id_diskon')->nullable();

            // === AGENT ===
            $table->string('agent', 100)->nullable();
            $table->bigInteger('fee_agent')->default(0)
                ->comment('Total fee agent untuk keluarga (dibagi rata per jamaah)');

            // === KEBERANGKATAN ===
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();
            $table->integer('bulan_harga')->nullable();
            $table->integer('tahun_harga')->nullable();
            $table->unsignedBigInteger('id_produk_keberangkatan')->nullable();
            $table->unsignedBigInteger('id_produk_harga_bulanan')->nullable();

            // === KEUANGAN ===
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            $table->bigInteger('nilai_diskon')->default(0)
                ->comment('Nilai diskon per orang');
            $table->bigInteger('total_diskon')->default(0)
                ->comment('Total diskon kelompok');
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', ['Belum Bayar', 'DP', 'Setoran', 'Lunas'])
                ->default('Belum Bayar');

            // === CATATAN ===
            $table->text('keterangan_diskon')->nullable();
            $table->text('catatan_tambahan')->nullable();

            $table->timestamps();

            // === FOREIGN KEYS ===
            $table->foreign('id_diskon')
                ->references('id_diskon')
                ->on('diskons')
                ->onDelete('set null');

            $table->foreign('id_produk_keberangkatan')
                ->references('id')
                ->on('produk_keberangkatans')
                ->onDelete('set null');

            $table->foreign('id_produk_harga_bulanan')
                ->references('id')
                ->on('produk_harga_bulanan')
                ->onDelete('set null');

            // === INDEX ===
            $table->index('kode_keluarga', 'idx_keluarga_kode');
            $table->index('nama_keluarga', 'idx_keluarga_nama');
            $table->index('status_pembayaran', 'idx_keluarga_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
