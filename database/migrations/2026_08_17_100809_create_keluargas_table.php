<?php

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
            $table->string('nama_keluarga', 100)->comment('Nama keluarga/kelompok');
            $table->string('produk_paket', 100)->nullable();
            $table->unsignedBigInteger('id_diskon')->nullable();
            $table->string('agent', 100)->nullable();
            $table->bigInteger('fee_agent')->default(0);

            // Keberangkatan (shared)
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();

            // Keuangan
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            $table->bigInteger('nilai_diskon')->default(0)->comment('Nilai diskon dalam Rupiah');
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', ['Belum Bayar', 'DP', 'Setoran', 'Lunas'])->default('Belum Bayar');
            $table->text('keterangan_diskon')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();

            $table->foreign('id_diskon')
                  ->references('id_diskon')
                  ->on('diskons')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
