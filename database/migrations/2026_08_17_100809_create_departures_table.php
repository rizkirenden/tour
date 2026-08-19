<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id('id_departure');
            $table->string('produk_paket', 100);
            $table->string('nama_keberangkatan', 100);
            $table->date('tanggal_keberangkatan');
            $table->date('tanggal_kepulangan');
            $table->integer('kuota')->default(0);
            $table->integer('jamaah_terdaftar')->default(0);
            $table->string('status', 50)->nullable();
            $table->bigInteger('total_pendapatan_kotor')->default(0);
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_pendapatan_bersih')->default(0);
            $table->bigInteger('total_hpp')->default(0);
            $table->bigInteger('laba_bersih')->default(0);
            $table->decimal('margin_laba', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
