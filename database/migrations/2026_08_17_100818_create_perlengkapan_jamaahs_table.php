<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perlengkapan_jamaahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jamaah');
            $table->unsignedBigInteger('id_departure_perlengkapan');
            $table->integer('jumlah')->default(1);
            $table->bigInteger('harga_satuan')->default(0);
            $table->bigInteger('total_harga')->default(0);
            $table->enum('status_terima', ['Belum Diterima', 'Sudah Diterima'])->default('Belum Diterima');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_jamaah')
                  ->references('id_jamaah')
                  ->on('jamaahs')
                  ->onDelete('cascade');
                  
            $table->foreign('id_departure_perlengkapan')
                  ->references('id')
                  ->on('departure_perlengkapan')
                  ->onDelete('cascade');
                  
            $table->unique(['id_jamaah', 'id_departure_perlengkapan'], 'unique_jamaah_perlengkapan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perlengkapan_jamaahs');
    }
};