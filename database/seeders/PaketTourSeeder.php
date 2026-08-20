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
                'kota_tujuan' => 'Mekkah & Madinah',
                'negara' => 'Arab Saudi',
                'durasi_hari' => 5,
                'deskripsi' => 'Tour ziarah ke tempat-tempat bersejarah di Mekkah dan Madinah',
                'harga_include' => 1,
                'harga_per_orang' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_paket_tour' => 2,
                'kota_tujuan' => 'Madinah',
                'negara' => 'Arab Saudi',
                'durasi_hari' => 3,
                'deskripsi' => 'Tour ziarah ke tempat-tempat bersejarah di Madinah',
                'harga_include' => 0,
                'harga_per_orang' => 3000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}