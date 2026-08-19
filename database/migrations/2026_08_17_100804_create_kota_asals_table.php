<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kota_asals', function (Blueprint $table) {
            $table->id('id_kota');
            $table->string('nama_kota', 50);
            $table->string('provinsi', 50)->nullable();
            $table->string('pulau', 20)->nullable();
            $table->string('bandara_terdekat', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kota_asals');
    }
};
