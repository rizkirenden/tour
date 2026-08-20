<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_paket_tour', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paket_tour');
            $table->unsignedBigInteger('id_hotel');
            $table->integer('durasi_menginap')->nullable();
            $table->bigInteger('harga_hotel')->nullable();
            $table->integer('urutan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key constraints - SESUAIKAN dengan primary key hotel
            $table->foreign('id_paket_tour')
                  ->references('id_paket_tour')
                  ->on('paket_tours')
                  ->onDelete('cascade');

            $table->foreign('id_hotel')
                  ->references('id_hotel')  // <-- Ganti 'id' menjadi 'id_hotel'
                  ->on('hotels')            // <-- Ganti 'hotel' menjadi 'hotels'
                  ->onDelete('cascade');

            // Unique constraint agar tidak ada duplikasi
            $table->unique(['id_paket_tour', 'id_hotel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_paket_tour');
    }
};
