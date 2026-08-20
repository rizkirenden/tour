<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_transaksis')->insert([
            [
                'id_jenis' => 1,
                'kode' => 'JTR-001',
                'nama' => 'Pembayaran Umroh',
                'keterangan' => 'Transaksi pembayaran paket umroh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jenis' => 2,
                'kode' => 'JTR-002',
                'nama' => 'Pembayaran Haji',
                'keterangan' => 'Transaksi pembayaran paket haji',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}