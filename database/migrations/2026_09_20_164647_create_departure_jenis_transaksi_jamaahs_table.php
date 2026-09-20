<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_jenis_transaksi_jamaah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_departure_jenis_transaksi');
            $table->unsignedBigInteger('id_jamaah');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status_terima', ['Belum Diterima', 'Sudah Diterima'])->default('Belum Diterima');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // ✅ Tentukan nama foreign key secara manual (short name)
            $table->foreign('id_departure_jenis_transaksi', 'fk_djt_jamaah_djt')
                ->references('id')
                ->on('departure_jenis_transaksis')
                ->onDelete('cascade');

            $table->foreign('id_jamaah', 'fk_djt_jamaah_jamaah')
                ->references('id_jamaah')
                ->on('jamaahs')
                ->onDelete('cascade');

            // ✅ Tentukan nama index manual juga
            $table->index(['id_departure_jenis_transaksi', 'id_jamaah'], 'idx_djt_jamaah_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_jenis_transaksi_jamaah');
    }
};