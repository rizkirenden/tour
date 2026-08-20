<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_pengeluarans')->insert([
            [
                'id_kategori' => 1,
                'nama_kategori' => 'Transportasi',
                'deskripsi' => 'Biaya transportasi pesawat, bus, dan lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2,
                'nama_kategori' => 'Akomodasi',
                'deskripsi' => 'Biaya hotel dan penginapan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}