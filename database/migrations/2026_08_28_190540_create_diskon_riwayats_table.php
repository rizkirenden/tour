<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diskon_riwayats', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->unsignedBigInteger('id_diskon');
            $table->string('nama_diskon', 100);
            $table->integer('nilai_diskon')->default(0);
            $table->string('berlaku_untuk_produk', 100)->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('sudah_digunakan')->default(0);
            $table->integer('kuota_baru')->nullable();
            $table->integer('reset_ke')->default(0);
            $table->text('catatan')->nullable();
            $table->string('direset_oleh', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_diskon')
                  ->references('id_diskon')
                  ->on('diskons')
                  ->onDelete('cascade');

            $table->index('id_diskon', 'idx_riwayat_diskon');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diskon_riwayats');
    }
};
