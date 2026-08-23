<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pembayarans', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_jamaah')->constrained('jamaahs', 'id_jamaah')->onDelete('cascade');
            $table->foreignId('id_metode_pembayaran')->constrained('metode_pembayarans', 'id_metode')->onDelete('restrict');
            $table->foreignId('id_jenis_transaksi')->constrained('jenis_transaksis', 'id_jenis')->onDelete('restrict');
            $table->date('tanggal_transaksi');
            $table->bigInteger('jumlah_bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            // Index untuk optimasi query
            $table->index(['id_jamaah', 'tanggal_transaksi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayarans');
    }
};
