<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_keberangkatans', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('nama_status', 50);
            $table->string('warna', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_keberangkatans');
    }
};
