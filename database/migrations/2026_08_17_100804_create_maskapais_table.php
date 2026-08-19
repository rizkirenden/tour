<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maskapais', function (Blueprint $table) {
            $table->id('id_maskapai');
            $table->string('kode_maskapai', 10)->unique();
            $table->string('nama_maskapai', 50);
            $table->enum('tipe_penerbangan', ['Domestik', 'Internasional']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maskapais');
    }
};
