<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id('id_keluarga');
            $table->unsignedBigInteger('id_departure');
            $table->string('kode_keluarga', 20)->unique();
            $table->string('nama_keluarga', 100);
            $table->text('alamat')->nullable();
            $table->string('telepon_rumah', 20)->nullable();
            $table->integer('jumlah_anggota')->default(0);
            $table->timestamps();

            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
