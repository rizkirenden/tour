<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_lain_lains', function (Blueprint $table) {
            $table->id('id_pengeluaran_lain');
            $table->date('tanggal');
            $table->string('nama_pengeluaran', 150);
            $table->string('kategori', 100)->nullable();
            $table->bigInteger('nominal')->default(0);
            $table->text('keterangan')->nullable();

            // Tanpa foreign key — tabel users belum ada
            $table->unsignedBigInteger('id_user')->nullable();

            $table->timestamps();

            $table->index('tanggal');
            $table->index('kategori');
            $table->index('id_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_lain_lains');
    }
};