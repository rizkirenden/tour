<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_hotel_per_bulans', function (Blueprint $table) {
            $table->id('id_harga_hotel');
            $table->string('hotel', 100);
            $table->string('lokasi')->nullable();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->bigInteger('harga_per_malam');
            $table->integer('kapasitas')->nullable();
            $table->string('tipe_kamar', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_hotel_per_bulans');
    }
};
