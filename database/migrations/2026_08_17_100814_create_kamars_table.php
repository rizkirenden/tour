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
            $table->foreignId('id_hotel')->constrained('hotels', 'id_hotel')->onDelete('cascade');
            $table->string('tipe_kamar', 50);
            $table->integer('kapasitas');
            $table->text('fasilitas_kamar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};