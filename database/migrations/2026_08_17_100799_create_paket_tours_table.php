<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_tours', function (Blueprint $table) {
            $table->id('id_paket_tour');
            $table->string('kota_tujuan', 50)->nullable();
            $table->string('negara', 50)->nullable();
            $table->integer('durasi_hari')->nullable();
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga_per_orang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_tours');
    }
};