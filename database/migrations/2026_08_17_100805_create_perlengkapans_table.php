<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perlengkapans', function (Blueprint $table) {
            $table->id('id_perlengkapan');
            $table->string('kode_perlengkapan', 20)->unique();
            $table->string('nama_perlengkapan', 100);
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga_satuan');
            $table->string('satuan', 20)->nullable();
            $table->enum('kategori', ['Koper', 'Pakaian', 'Aksesoris', 'Dokumen', 'Lainnya'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perlengkapans');
    }
};
