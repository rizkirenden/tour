<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hpp_details', function (Blueprint $table) {
            $table->id('id_hpp');
            $table->string('departure', 100);
            $table->string('jenis_biaya', 50);
            $table->string('nama_item', 100);
            $table->integer('jumlah')->default(0);
            $table->bigInteger('harga_satuan')->default(0);
            $table->bigInteger('total_biaya')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpp_details');
    }
};
