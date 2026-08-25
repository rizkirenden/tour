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
            $table->foreignId('id_paket_tour')->constrained('paket_tours', 'id_paket_tour')->onDelete('cascade');
            $table->foreignId('id_hotel')->constrained('hotels', 'id_hotel')->onDelete('cascade');
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->unique(['id_paket_tour', 'id_hotel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_paket_tour');
    }
};