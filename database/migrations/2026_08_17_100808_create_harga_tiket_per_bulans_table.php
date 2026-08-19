<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_tiket_per_bulans', function (Blueprint $table) {
            $table->id('id_harga');
            $table->string('kota_asal', 50);
            $table->string('pulau', 20)->nullable();
            $table->string('bandara', 50)->nullable();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->enum('tipe_tiket', ['Domestik Pergi', 'Domestik Pulang', 'Internasional']);
            $table->string('kode_maskapai', 10)->nullable();
            $table->string('nama_maskapai', 50)->nullable();
            $table->string('kelas', 20)->nullable();
            $table->bigInteger('harga')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_tiket_per_bulans');
    }
};
