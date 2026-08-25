<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_hotel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_departure');
            $table->unsignedBigInteger('id_hotel');
            $table->unsignedBigInteger('id_kamar')->nullable();
            $table->string('tipe_kamar', 100);
            $table->integer('jumlah_kamar')->default(1);
            $table->bigInteger('harga_per_malam')->default(0);
            $table->integer('durasi_menginap')->default(1);
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');
                  
            $table->foreign('id_hotel')
                  ->references('id_hotel')
                  ->on('hotels')
                  ->onDelete('cascade');
                  
            $table->foreign('id_kamar')
                  ->references('id_kamar')
                  ->on('kamars')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_hotel_details');
    }
};