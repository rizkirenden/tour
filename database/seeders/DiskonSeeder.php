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
                'id_diskon' => 1,
                'kode_diskon' => 'DSC-001',
                'nama_diskon' => 'Diskon Early Bird',
                'persen_diskon' => 10.00,
                'berlaku_untuk_produk' => 'Umroh Executive',
                'kuota' => 50,
                'sudah_digunakan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_diskon' => 2,
                'kode_diskon' => 'DSC-002',
                'nama_diskon' => 'Diskon Grup',
                'persen_diskon' => 15.00,
                'berlaku_untuk_produk' => 'Umroh Premium',
                'kuota' => 20,
                'sudah_digunakan' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}