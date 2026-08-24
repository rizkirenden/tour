<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id('id_hotel');
            $table->string('nama_hotel', 100);
            $table->string('lokasi')->nullable();
            $table->string('tipe_hotel')->nullable();
            $table->integer('bintang')->nullable();
            $table->string('negara', 50)->nullable();
            $table->string('kota', 50)->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
