<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->string('kategori_pengeluaran', 100);
            $table->string('departure', 100)->nullable();
            $table->date('tanggal_pengeluaran');
            $table->text('deskripsi')->nullable();
            $table->bigInteger('jumlah');
            $table->string('metode', 20)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->enum('status_approval', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pengeluarans');
    }
};
