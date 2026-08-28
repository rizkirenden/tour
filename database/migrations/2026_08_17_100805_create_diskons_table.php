<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diskons', function (Blueprint $table) {
            $table->id('id_diskon');
            $table->string('nama_diskon', 100);
            $table->integer('nilai_diskon')->default(0)->comment('Nilai diskon dalam Rupiah');
            $table->string('berlaku_untuk_produk', 100)->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('sudah_digunakan')->default(0);
            $table->integer('reset_count')->default(0)->comment('Jumlah kali reset');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
