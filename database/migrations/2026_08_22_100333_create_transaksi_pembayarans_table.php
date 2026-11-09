<?php
// database/migrations/xxxx_xx_xx_create_transaksi_pembayarans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pembayarans', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_jamaah');
            $table->unsignedBigInteger('id_metode_pembayaran');

            // ⚠️ id_jenis_transaksi dibuat NULLABLE (opsional, hanya untuk kompatibilitas)
            $table->unsignedBigInteger('id_jenis_transaksi')->nullable();

            // ✅ id_jenis_pembayaran — sumber utama sekarang
            $table->unsignedBigInteger('id_jenis_pembayaran')->nullable();

            $table->date('tanggal_transaksi');
            $table->bigInteger('jumlah_bayar');

            // === JENIS MUTASI: masuk (pembayaran) / keluar (refund) ===
            $table->string('jenis_mutasi', 20)->default('masuk')
                ->comment('masuk = pembayaran, keluar = refund/pengembalian');

            $table->string('bukti_pembayaran', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_refund')->nullable()
                ->comment('Alasan / catatan khusus refund');

            $table->string('created_by', 100)->nullable();
            $table->timestamps();

            // === FOREIGN KEYS ===
            $table->foreign('id_jamaah')
                ->references('id_jamaah')
                ->on('jamaahs')
                ->onDelete('cascade');

            $table->foreign('id_metode_pembayaran')
                ->references('id_metode')
                ->on('metode_pembayarans')
                ->onDelete('cascade');

            $table->foreign('id_jenis_transaksi')
                ->references('id_jenis')
                ->on('jenis_transaksis')
                ->onDelete('set null');       // ← set null, bukan cascade

            $table->foreign('id_jenis_pembayaran')
                ->references('id_jenis_pembayaran')
                ->on('jenis_pembayarans')
                ->onDelete('set null');

            // === INDEX ===
            $table->index('id_jamaah', 'idx_transaksi_jamaah');
            $table->index('tanggal_transaksi', 'idx_transaksi_tanggal');
            $table->index('jenis_mutasi', 'idx_transaksi_mutasi');
            $table->index('id_jenis_pembayaran', 'idx_transaksi_jenis_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayarans');
    }
};
