<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayarans', function (Blueprint $table) {
            $table->id('id_metode');
            $table->enum('jenis_pembayaran', ['bank_transfer', 'cash', 'e_wallet'])->default('bank_transfer');
            $table->string('kode_bank', 10)->nullable()->unique();
            $table->string('nama_bank', 50)->nullable();
            $table->string('nomor_rekening', 20)->nullable();
            $table->string('atas_nama', 100)->nullable();
            $table->string('e_wallet_type', 50)->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayarans');
    }
};
