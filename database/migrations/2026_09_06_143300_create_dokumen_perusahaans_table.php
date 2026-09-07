<?php
// database/migrations/2024_01_01_000000_create_dokumen_perusahaans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_perusahaans', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->string('jenis'); // logo, ttd, cap
            $table->string('path');
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_perusahaans');
    }
};
