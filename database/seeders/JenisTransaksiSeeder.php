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
            ['nama' => 'Air Zam Zam',     'keterangan' => 'Biaya air zam zam'],
            ['nama' => 'Handling Pergi',  'keterangan' => 'Biaya handling keberangkatan'],
            ['nama' => 'Handling Pulang', 'keterangan' => 'Biaya handling kepulangan'],
            ['nama' => 'Visa',            'keterangan' => 'Biaya pengurusan visa'],
            ['nama' => 'Asuransi',        'keterangan' => 'Biaya asuransi perjalanan'],
            ['nama' => 'Tiket Pesawat',   'keterangan' => 'Biaya tiket pesawat'],
            ['nama' => 'Hotel',           'keterangan' => 'Biaya akomodasi hotel'],
            ['nama' => 'Transportasi',    'keterangan' => 'Biaya transportasi lokal'],
            ['nama' => 'Konsumsi',        'keterangan' => 'Biaya konsumsi jamaah'],
            ['nama' => 'Perlengkapan',    'keterangan' => 'Biaya perlengkapan jamaah'],
            ['nama' => 'Lain-lain',       'keterangan' => 'Biaya operasional lainnya'],
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
