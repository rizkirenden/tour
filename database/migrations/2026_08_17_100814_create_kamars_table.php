<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->unsignedBigInteger('id_departure');
            $table->unsignedBigInteger('id_hotel');
            $table->string('lokasi', 50)->nullable();
            $table->string('nomor_kamar', 20)->nullable();
            $table->string('tipe_kamar', 20)->nullable();
            $table->integer('kapasitas')->default(4);
            $table->bigInteger('harga_per_malam')->default(0);
            $table->integer('total_malam')->default(0);
            $table->bigInteger('total_biaya')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');

            $table->foreign('id_hotel')
                  ->references('id_hotel')
                  ->on('hotels')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
