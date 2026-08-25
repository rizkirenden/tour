<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_jenis_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_departure');
            $table->unsignedBigInteger('id_jenis_transaksi');
            $table->decimal('harga_satuan', 15, 2)->default(0); // harga per orang
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');

            $table->foreign('id_jenis_transaksi')
                  ->references('id_jenis')
                  ->on('jenis_transaksis')
                  ->onDelete('cascade');

            $table->unique(['id_departure', 'id_jenis_transaksi'], 'departure_jenis_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_jenis_transaksis');
    }
};
