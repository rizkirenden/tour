<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar_jamaahs', function (Blueprint $table) {
            $table->id('id_kamar_jamaah');
            $table->unsignedBigInteger('id_kamar');
            $table->unsignedBigInteger('id_jamaah');
            $table->integer('posisi_tempat_tidur')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();

            $table->foreign('id_kamar')
                  ->references('id_kamar')
                  ->on('kamars')
                  ->onDelete('cascade');

            $table->foreign('id_jamaah')
                  ->references('id_jamaah')
                  ->on('jamaahs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar_jamaahs');
    }
};
