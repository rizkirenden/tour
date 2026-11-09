<?php
// database/seeders/JenisPembayaranSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'DP',        'keterangan' => 'Down payment / pembayaran awal'],
            ['nama' => 'Angsuran',  'keterangan' => 'Pembayaran cicilan / angsuran'],
            ['nama' => 'Lunas',     'keterangan' => 'Pelunasan pembayaran penuh'],
            ['nama' => 'Refund',    'keterangan' => 'Pengembalian dana ke jamaah (kelebihan bayar / pindah produk)'],
        ];

        foreach ($data as $item) {
            DB::table('jenis_pembayarans')->updateOrInsert(
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
