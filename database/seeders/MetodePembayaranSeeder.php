<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('metode_pembayarans')->insert([
            [
                'id_metode' => 1,
                'kode_bank' => 'BCA',
                'nama_bank' => 'Bank Central Asia',
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'PT Arrum Tour',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_metode' => 2,
                'kode_bank' => 'MANDIRI',
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '0987654321',
                'atas_nama' => 'PT Arrum Tour',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}