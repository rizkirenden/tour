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
                  $table->unsignedBigInteger('id_produk')->nullable();
                  $table->string('produk_paket', 100)->nullable();
                  $table->string('kode_keberangkatan', 50)->unique();
                  $table->string('nama_keberangkatan', 100);
                  $table->date('tanggal_keberangkatan');
                  $table->date('tanggal_kepulangan');
                  $table->integer('bulan_keberangkatan')->nullable();
                  $table->integer('tahun_keberangkatan')->nullable();
                  $table->integer('kuota')->default(0);
                  $table->integer('jamaah_terdaftar')->default(0);
                  $table->unsignedBigInteger('id_status')->nullable();

                  // Maskapai
                  $table->unsignedBigInteger('id_maskapai_domestik_berangkat')->nullable();
                  $table->bigInteger('harga_maskapai_domestik_berangkat')->default(0);
                  $table->unsignedBigInteger('id_maskapai_domestik_pulang')->nullable();
                  $table->bigInteger('harga_maskapai_domestik_pulang')->default(0);
                  $table->unsignedBigInteger('id_maskapai_internasional_berangkat')->nullable();
                  $table->bigInteger('harga_maskapai_internasional_berangkat')->default(0);
                  $table->unsignedBigInteger('id_maskapai_internasional_pulang')->nullable();
                  $table->bigInteger('harga_maskapai_internasional_pulang')->default(0);

                  // Hotel
                  $table->unsignedBigInteger('id_hotel_mekkah')->nullable();
                  $table->unsignedBigInteger('id_hotel_madinah')->nullable();
                  $table->unsignedBigInteger('id_hotel_transit')->nullable();

                  // Keuangan
                  $table->bigInteger('total_pendapatan')->default(0);
                  $table->bigInteger('total_diskon')->default(0);
                  $table->bigInteger('total_pendapatan_bersih')->default(0);
                  $table->bigInteger('total_pendapatan_kotor')->default(0); // TAMBAHKAN
                  $table->bigInteger('total_pengeluaran')->default(0);
                  $table->bigInteger('keuntungan')->default(0); // TAMBAHKAN
                  $table->decimal('margin_laba', 10, 2)->default(0);

                  // Status Complete
                  $table->boolean('is_maskapai_complete')->default(false);
                  $table->boolean('is_hotel_complete')->default(false);
                  $table->boolean('is_jamaah_complete')->default(false);
                  $table->boolean('is_catatan_complete')->default(false);
                  $table->boolean('is_perlengkapan_complete')->default(false);

                  $table->text('catatan')->nullable();
                  $table->timestamps();

                  // Foreign Keys
                  $table->foreign('id_produk')
                        ->references('id_produk')
                        ->on('produk_pakets')
                        ->onDelete('set null');

                  $table->foreign('id_status')
                        ->references('id_status')
                        ->on('status_keberangkatans')
                        ->onDelete('set null');

                  $table->foreign('id_maskapai_domestik_berangkat')
                        ->references('id_maskapai')
                        ->on('maskapais')
                        ->onDelete('set null');

                  $table->foreign('id_maskapai_domestik_pulang')
                        ->references('id_maskapai')
                        ->on('maskapais')
                        ->onDelete('set null');

                  $table->foreign('id_maskapai_internasional_berangkat')
                        ->references('id_maskapai')
                        ->on('maskapais')
                        ->onDelete('set null');

                  $table->foreign('id_maskapai_internasional_pulang')
                        ->references('id_maskapai')
                        ->on('maskapais')
                        ->onDelete('set null');

                  $table->foreign('id_hotel_mekkah')
                        ->references('id_hotel')
                        ->on('hotels')
                        ->onDelete('set null');

                  $table->foreign('id_hotel_madinah')
                        ->references('id_hotel')
                        ->on('hotels')
                        ->onDelete('set null');

                  $table->foreign('id_hotel_transit')
                        ->references('id_hotel')
                        ->on('hotels')
                        ->onDelete('set null');

                  // Index
                  $table->index('kode_keberangkatan');
                  $table->index('tanggal_keberangkatan');
                  $table->index('id_status');
            });
      }

      public function down(): void
      {
            Schema::dropIfExists('departures');
      }
};
