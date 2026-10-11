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

            // ==========================================
            // KEBERANGKATAN
            // ==========================================
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();

            // ==========================================
            // HARGA (TERPISAH DARI KEBERANGKATAN)
            // ==========================================
            $table->integer('bulan_harga')->nullable()->comment('Bulan harga bisa berbeda dari keberangkatan');
            $table->integer('tahun_harga')->nullable()->comment('Tahun harga bisa berbeda dari keberangkatan');

            // ==========================================
            // REFERENSI KE PRODUK
            // ==========================================
            $table->unsignedBigInteger('id_produk_keberangkatan')->nullable();
            $table->unsignedBigInteger('id_produk_harga_bulanan')->nullable();

            // ==========================================
            // KEUANGAN
            // ==========================================
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

            // ==========================================
            // FOREIGN KEYS
            // ==========================================
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

            // ==========================================
            // INDEX
            // ==========================================
            $table->index('nama_keluarga');
            $table->index('kode_keluarga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
