<?php
// database/seeders/JenisTransaksiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'DP',                         'keterangan' => 'Pembayaran awal / down payment'],
            ['nama' => 'Angsuran',                   'keterangan' => 'Pembayaran cicilan'],
            ['nama' => 'Pelunasan',                  'keterangan' => 'Pembayaran pelunasan'],
            ['nama' => 'Pengembalian Dana (Refund)', 'keterangan' => 'Pengembalian dana ke jamaah (kelebihan bayar / pindah produk)'],
        ];

        foreach ($data as $item) {
            DB::table('jenis_transaksis')->updateOrInsert(
                ['nama' => $item['nama']],
                [
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
