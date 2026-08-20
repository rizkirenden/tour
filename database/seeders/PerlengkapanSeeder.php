<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerlengkapanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perlengkapans')->insert([
            [
                'id_perlengkapan' => 1,
                'kode_perlengkapan' => 'PRL-001',
                'nama_perlengkapan' => 'Koper Ukuran Besar',
                'deskripsi' => 'Koper 28 inch dengan roda 360 derajat',
                'harga_satuan' => 750000,
                'satuan' => 'Buah',
                'kategori' => 'Koper',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_perlengkapan' => 2,
                'kode_perlengkapan' => 'PRL-002',
                'nama_perlengkapan' => 'Baju Ihram',
                'deskripsi' => 'Baju ihram putih 2 helai untuk pria',
                'harga_satuan' => 250000,
                'satuan' => 'Set',
                'kategori' => 'Pakaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}