<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_jamaahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_departure');
            $table->unsignedBigInteger('id_jamaah');
            $table->enum('status_keberangkatan', ['Terdaftar', 'Berangkat', 'Kembali', 'Batal'])->default('Terdaftar');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');
                  
            $table->foreign('id_jamaah')
                  ->references('id_jamaah')
                  ->on('jamaahs')
                  ->onDelete('cascade');
                  
            $table->unique(['id_departure', 'id_jamaah']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_jamaahs');
    }
};