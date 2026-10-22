<?php
// database/migrations/xxxx_xx_xx_create_jamaahs_table.php

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

            // === DATA PRIBADI ===
            $table->string('nama_lengkap', 100);
            $table->string('nik', 20)->nullable()->comment('Nomor Induk Kependudukan');
            $table->string('nama_ayah', 100)->nullable()->comment('Nama ayah kandung');
            $table->string('pekerjaan', 100)->nullable()->comment('Pekerjaan jamaah');
            $table->string('telepon', 20)->nullable();
            $table->string('wa', 20)->nullable()->comment('Nomor WhatsApp');

            // === ALAMAT LENGKAP ===
            $table->text('alamat')->nullable();
            $table->string('kota_kabupaten', 100)->nullable()->comment('Kota/Kabupaten domisili');
            $table->string('kecamatan', 100)->nullable()->comment('Kecamatan domisili');
            $table->string('kelurahan', 100)->nullable()->comment('Kelurahan/Desa domisili');

            // === DATA PASSPORT ===
            $table->string('nomor_paspor', 20)->nullable();
            $table->date('paspor_expired')->nullable()->comment('Tanggal berakhir paspor');
            $table->date('paspor_terbit')->nullable()->comment('Tanggal terbit paspor');
            $table->string('paspor_diterbitkan_di', 100)->nullable()->comment('Tempat paspor diterbitkan');

            // === DATA LAHIR ===
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

            // === ✅ STATUS KAWIN (PER JAMAAH) ===
            $table->enum('status_kawin', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])
                ->nullable()
                ->comment('Status pernikahan jamaah');

            // === DATA KEBERANGKATAN ===
            $table->string('kota_asal', 50)->nullable();
            $table->string('pulau', 20)->nullable();
            $table->string('bandara_keberangkatan', 50)->nullable();
            $table->integer('bulan_harga')->nullable();
            $table->integer('tahun_harga')->nullable();
            $table->integer('bulan_keberangkatan')->nullable();
            $table->integer('tahun_keberangkatan')->nullable();

            // === FILE UPLOAD ===
            $table->string('file_ktp_kk', 255)->nullable()->comment('File KTP/KK');
            $table->string('file_vaksin', 255)->nullable()->comment('File Vaksin');
            $table->string('file_visa', 255)->nullable()->comment('File Visa');
            $table->string('file_paspor', 255)->nullable()->comment('File Passport');

            // === KEAMANAN ===
            $table->string('encryption_key', 100)->nullable();

            // === AGENT ===
            $table->string('agent_name', 100)->nullable()->comment('Nama Agent');
            $table->bigInteger('fee_agent')->default(0)
                ->comment('Fee agent per jamaah (hasil bagi dari total keluarga)');

            // === PEMBUATAN PASSPORT ===
            $table->boolean('is_pembuatan_passport')->default(false);
            $table->bigInteger('pendampingan_fee')->default(0)
                ->comment('Pembayaran passport');
            $table->bigInteger('pendampingan_fee_petugas')->default(0)
                ->comment('Pendapatan perusahaan');

            // === TIKET ===
            $table->bigInteger('harga_tiket_pergi_domestik')->default(0);
            $table->bigInteger('harga_tiket_pulang_domestik')->default(0);
            $table->bigInteger('total_tiket_domestik')->default(0);
            $table->bigInteger('harga_tiket_pergi_international')->default(0);
            $table->bigInteger('harga_tiket_pulang_international')->default(0);
            $table->bigInteger('total_tiket_international')->default(0);

            // === HOTEL ===
            $table->string('hotel_mekkah', 100)->nullable();
            $table->string('hotel_madinah', 100)->nullable();
            $table->string('hotel_transit', 100)->nullable();

            // === KEUANGAN ===
            $table->bigInteger('total_tagihan_sebelum_diskon')->default(0);
            $table->bigInteger('nilai_diskon')->default(0);
            $table->bigInteger('total_diskon')->default(0);
            $table->bigInteger('total_tagihan_setelah_diskon')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);
            $table->enum('status_pembayaran', ['Belum Bayar', 'DP', 'Setoran', 'Lunas'])
                ->default('Belum Bayar');

            $table->text('keterangan_diskon')->nullable();
            $table->text('catatan_tambahan')->nullable();

            $table->timestamps();

            // === FOREIGN KEYS ===
            $table->foreign('id_keluarga')
                ->references('id_keluarga')
                ->on('keluargas')
                ->onDelete('set null');

            $table->foreign('id_diskon')
                ->references('id_diskon')
                ->on('diskons')
                ->onDelete('set null');

            // === INDEX ===
            $table->index('nik', 'idx_jamaah_nik');
            $table->index('nomor_paspor', 'idx_jamaah_paspor');
            $table->index('nama_lengkap', 'idx_jamaah_nama');
            $table->index('status_pembayaran', 'idx_jamaah_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
