<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->string('nomor_invoice', 50)->unique();
            $table->string('jamaah', 100);
            $table->string('departure', 100);
            $table->date('tanggal_terbit');
            $table->date('tanggal_jatuh_tempo');
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            $table->decimal('persen_diskon', 5, 2)->default(0);
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_invoice', ['Draft', 'Terbit', 'Lunas', 'Jatuh Tempo'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
