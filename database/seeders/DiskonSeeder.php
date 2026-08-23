<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiskonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('diskons')->insert([
            [
                'nama_diskon' => 'Diskon Early Bird',
                'nilai_diskon' => 2000000, // Rp 2.000.000
                'berlaku_untuk_produk' => 'Umroh Executive 12 Hari',
                'kuota' => 50,
                'sudah_digunakan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_diskon' => 'Diskon Grup',
                'nilai_diskon' => 1500000, // Rp 1.500.000
                'berlaku_untuk_produk' => 'Umroh Premium 9 Hari',
                'kuota' => 20,
                'sudah_digunakan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_diskon' => 'Diskon Spesial Ramadhan',
                'nilai_diskon' => 3000000, // Rp 3.000.000
                'berlaku_untuk_produk' => 'Semua Produk',
                'kuota' => 10,
                'sudah_digunakan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
