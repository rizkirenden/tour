<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_perlengkapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_departure');
            $table->unsignedBigInteger('id_perlengkapan');
            $table->integer('jumlah_per_jamaah')->default(1);
            $table->bigInteger('harga_satuan')->default(0);
            $table->bigInteger('total_harga')->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('id_departure')
                  ->references('id_departure')
                  ->on('departures')
                  ->onDelete('cascade');
                  
            $table->foreign('id_perlengkapan')
                  ->references('id_perlengkapan')
                  ->on('perlengkapans')
                  ->onDelete('cascade');
                  
            $table->unique(['id_departure', 'id_perlengkapan'], 'unique_departure_perlengkapan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_perlengkapan');
    }
};