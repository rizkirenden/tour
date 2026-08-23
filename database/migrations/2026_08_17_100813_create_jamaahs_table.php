<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id('id_jamaah');
            $table->string('id_keberangkatan', 100);
            $table->unsignedBigInteger('id_keluarga')->nullable();
            $table->string('hubungan_keluarga', 30)->nullable();
            $table->boolean('is_kepala_keluarga')->default(false);
            $table->string('produk_paket', 100);
            $table->unsignedBigInteger('id_diskon')->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('nomor_paspor', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('kota_asal', 50)->nullable();
            $table->string('pulau', 20)->nullable();
            $table->string('bandara_keberangkatan', 50)->nullable();
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_vaksin')->nullable();
            $table->string('foto_visa')->nullable();
            $table->string('encryption_key', 100)->nullable();
            $table->string('jenis_pendampingan', 30)->nullable();
            $table->string('agent', 100)->nullable();
            $table->bigInteger('fee_agent')->default(0);
            $table->bigInteger('harga_tiket_pergi_domestik')->default(0);
            $table->bigInteger('harga_tiket_pulang_domestik')->default(0);
            $table->bigInteger('total_tiket_domestik')->default(0);
            $table->bigInteger('harga_tiket_pergi_international')->default(0);
            $table->bigInteger('harga_tiket_pulang_international')->default(0);
            $table->bigInteger('total_tiket_international')->default(0);
            $table->string('hotel_mekkah', 100)->nullable();
            $table->string('hotel_madinah', 100)->nullable();
            $table->string('hotel_transit', 100)->nullable();
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            // HAPUS persen_diskon, hanya gunakan nilai_diskon
            $table->bigInteger('nilai_diskon')->default(0)->comment('Nilai diskon per orang dalam Rupiah');
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', ['Belum Bayar', 'DP', 'Setoran', 'Lunas'])->default('Belum Bayar');
            $table->text('keterangan_diskon')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();

            $table->foreign('id_keluarga')
                  ->references('id_keluarga')
                  ->on('keluargas')
                  ->onDelete('set null');

            $table->foreign('id_diskon')
                  ->references('id_diskon')
                  ->on('diskons')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
