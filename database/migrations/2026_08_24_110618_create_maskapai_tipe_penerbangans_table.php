<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maskapai_tipe_penerbangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_maskapai')->constrained('maskapais', 'id_maskapai')->onDelete('cascade');
            $table->enum('tipe_penerbangan', ['Domestik', 'Internasional']);
            $table->timestamps();

            $table->unique(['id_maskapai', 'tipe_penerbangan'], 'unique_maskapai_tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maskapai_tipe_penerbangans');
    }
};
