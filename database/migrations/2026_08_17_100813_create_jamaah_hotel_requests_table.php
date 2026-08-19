<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_hotel_requests', function (Blueprint $table) {
            $table->id('id_request');
            $table->string('jamaah', 100);
            $table->string('departure', 100);
            $table->string('hotel_default', 100)->nullable();
            $table->string('hotel_request', 100);
            $table->enum('lokasi', ['Mekkah', 'Madinah', 'Transit']);
            $table->bigInteger('harga_default_per_malam')->default(0);
            $table->bigInteger('harga_request_per_malam')->default(0);
            $table->bigInteger('selisih_per_malam')->default(0);
            $table->integer('durasi_menginap')->default(0);
            $table->bigInteger('total_selisih')->default(0);
            $table->text('alasan_request')->nullable();
            $table->date('tanggal_request');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_hotel_requests');
    }
};
