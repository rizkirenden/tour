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

            // Agent
            $table->string('agent', 100)->nullable();
            $table->bigInteger('fee_agent')->default(0);

            // Keberangkatan & Harga
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();
            $table->integer('bulan_harga')->nullable();
            $table->integer('tahun_harga')->nullable();
            $table->unsignedBigInteger('id_produk_keberangkatan')->nullable();
            $table->unsignedBigInteger('id_produk_harga_bulanan')->nullable();

            // Keuangan
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            $table->bigInteger('nilai_diskon')->default(0);
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);

            // ✅ REFUND / PENGEMBALIAN DANA
            $table->bigInteger('total_refund')->default(0)
                ->comment('Total dana yang sudah dikembalikan ke keluarga');
            $table->bigInteger('sisa_kelebihan_bayar')->default(0)
                ->comment('Kelebihan bayar yang BELUM di-refund (dibayar > tagihan)');

            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', ['Belum Bayar', 'DP', 'Setoran', 'Lunas'])
                ->default('Belum Bayar');

            $table->text('keterangan_diskon')->nullable();
            $table->text('catatan_tambahan')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_diskon')
                ->references('id_diskon')
                ->on('diskons')
                ->onDelete('set null');

            // Index
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
