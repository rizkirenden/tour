<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_paket_tour_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_departure')->constrained('departures', 'id_departure')->onDelete('cascade');
            $table->foreignId('id_paket_tour')->constrained('paket_tours', 'id_paket_tour')->onDelete('cascade');
            $table->foreignId('id_hotel')->constrained('hotels', 'id_hotel')->onDelete('cascade');

            // HAPUS ->after('id_hotel')
            $table->unsignedBigInteger('id_kamar')->nullable();

            $table->integer('urutan')->default(0);
            $table->integer('harga_per_malam')->default(0);
            $table->integer('durasi_menginap')->default(1);
            $table->integer('jumlah_kamar')->default(1);
            $table->string('tipe_kamar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['id_departure', 'id_paket_tour']);

            $table->foreign('id_kamar')
                ->references('id_kamar')
                ->on('kamars')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_paket_tour_hotels');
    }
};
