<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KotaAsalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kota_asals')->insert([
            [
                'id_kota' => 1,
                'nama_kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'pulau' => 'Jawa',
                'bandara_terdekat' => 'Soekarno-Hatta (CGK)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kota' => 2,
                'nama_kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'pulau' => 'Jawa',
                'bandara_terdekat' => 'Juanda (SUB)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}