<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_upgrade_hotels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_jamaah')->nullable()
                ->comment('null jika upgrade level keluarga');
            $table->unsignedBigInteger('id_keluarga')->nullable()
                ->comment('terisi jika upgrade level keluarga');

            $table->unsignedBigInteger('id_hotel');
            $table->unsignedBigInteger('id_kamar')->nullable();
            $table->string('tipe_kamar', 100)->nullable();

            // HARGA PER PAX
            $table->bigInteger('harga_per_pax')->default(0);
            $table->integer('jumlah_pax')->default(1);
            $table->bigInteger('total_harga')->default(0);

            // Field lama (info saja, tidak dipakai kalkulasi)
            $table->integer('jumlah_kamar')->default(1)->nullable();
            $table->integer('durasi_menginap')->default(1)->nullable();
            $table->bigInteger('harga_per_malam')->default(0)->nullable();

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_jamaah')->references('id_jamaah')->on('jamaahs')->onDelete('cascade');
            $table->foreign('id_keluarga')->references('id_keluarga')->on('keluargas')->onDelete('cascade');
            $table->foreign('id_hotel')->references('id_hotel')->on('hotels')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamars')->onDelete('set null');

            $table->index('id_jamaah');
            $table->index('id_keluarga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_upgrade_hotels');
    }
};
