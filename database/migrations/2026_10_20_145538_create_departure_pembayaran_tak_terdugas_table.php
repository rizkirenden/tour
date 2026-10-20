<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_pembayaran_tak_terduga', function (Blueprint $table) {
            $table->id('id_pembayaran_tak_terduga');
            $table->unsignedBigInteger('id_departure');
            $table->string('nama_pembayaran', 150);
            $table->bigInteger('total_pembayaran')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_departure')
                ->references('id_departure')
                ->on('departures')
                ->onDelete('cascade');

            $table->index('id_departure');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_pembayaran_tak_terduga');
    }
};