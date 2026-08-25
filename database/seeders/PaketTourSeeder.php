<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketTourSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paket_tours')->insert([
            [
                'id_paket_tour' => 1,
                'kota_tujuan' => 'Inggris',
                'negara' => 'England',
                'durasi_hari' => 5,
                'deskripsi' => 'Stadion',
                'harga_per_orang' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_paket_tour' => 2,
                'kota_tujuan' => 'Madrid',
                'negara' => 'Spanyol',
                'durasi_hari' => 3,
                'deskripsi' => 'Stadion',
                'harga_per_orang' => 3000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}