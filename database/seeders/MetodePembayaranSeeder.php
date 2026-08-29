<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key constraints
        Schema::disableForeignKeyConstraints();

        // Hapus data lama
        DB::table('metode_pembayarans')->truncate();

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();

        $metodePembayaran = [
            // ============ BANK TRANSFER ============
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'BCA',
                'nama_bank' => 'Bank Central Asia',
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'MANDIRI',
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '0987654321',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'BNI',
                'nama_bank' => 'Bank Negara Indonesia',
                'nomor_rekening' => '1122334455',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'BRI',
                'nama_bank' => 'Bank Rakyat Indonesia',
                'nomor_rekening' => '5544332211',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'BTN',
                'nama_bank' => 'Bank Tabungan Negara',
                'nomor_rekening' => '6677889900',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'CIMB',
                'nama_bank' => 'CIMB Niaga',
                'nomor_rekening' => '9988776655',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'PERMATA',
                'nama_bank' => 'Bank Permata',
                'nomor_rekening' => '4433221100',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'OCBC',
                'nama_bank' => 'OCBC NISP',
                'nomor_rekening' => '5566778899',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'DANAMON',
                'nama_bank' => 'Bank Danamon',
                'nomor_rekening' => '3344556677',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'bank_transfer',
                'kode_bank' => 'MAYBANK',
                'nama_bank' => 'Maybank',
                'nomor_rekening' => '1122003344',
                'atas_nama' => 'PT Arrum Tour',
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============ CASH / TUNAI ============
            [
                'jenis_pembayaran' => 'cash',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => null,
                'nomor_telepon' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ============ E-WALLET ============
            [
                'jenis_pembayaran' => 'e_wallet',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => 'OVO',
                'nomor_telepon' => '081234567890',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'e_wallet',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => 'GoPay',
                'nomor_telepon' => '081234567891',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'e_wallet',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => 'DANA',
                'nomor_telepon' => '081234567892',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'e_wallet',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => 'ShopeePay',
                'nomor_telepon' => '081234567893',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'jenis_pembayaran' => 'e_wallet',
                'kode_bank' => null,
                'nama_bank' => null,
                'nomor_rekening' => null,
                'atas_nama' => null,
                'e_wallet_type' => 'LinkAja',
                'nomor_telepon' => '081234567894',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('metode_pembayarans')->insert($metodePembayaran);

        $this->command->info('Metode Pembayaran berhasil di-seed!');
        $this->command->info('Total Metode Pembayaran: ' . DB::table('metode_pembayarans')->count());
        $this->command->info('');
        $this->command->info('Detail Metode Pembayaran:');
        $this->command->info('  - Bank Transfer: ' . DB::table('metode_pembayarans')->where('jenis_pembayaran', 'bank_transfer')->count() . ' metode');
        $this->command->info('  - Cash: ' . DB::table('metode_pembayarans')->where('jenis_pembayaran', 'cash')->count() . ' metode');
        $this->command->info('  - E-Wallet: ' . DB::table('metode_pembayarans')->where('jenis_pembayaran', 'e_wallet')->count() . ' metode');
    }
}
