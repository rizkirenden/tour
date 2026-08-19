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
            $table->string('kode_diskon', 50)->unique();
            $table->string('nama_diskon', 100);
            $table->decimal('persen_diskon', 5, 2);
            $table->string('berlaku_untuk_produk', 100)->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('sudah_digunakan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
